<?php
/**
 * @copyright Roy Rosenzweig Center for History and New Media, 2007-2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Omeka
 */

/**
 * @package Omeka
 * @subpackage Models
 * @author CHNM
 * @copyright Roy Rosenzweig Center for History and New Media, 2007-2010
 */
class TagTable extends Omeka_Db_Table
{    
    public function findOrNew($name) 
    {
        $db = $this->getDb();
        $sql = "
        SELECT t.* 
        FROM {$db->Tag} t 
        WHERE t.name COLLATE utf8_bin LIKE ? 
        LIMIT 1";
        $tag = $this->fetchObject($sql, array($name));
        
        if (!$tag) {
            $tag = new Tag;
            $tag->name = $name;
        }
        
        return $tag;
    }
    
    /**
     * Filter a SELECT statement based on an Omeka_Record instance
     * 
     * @param Omeka_Db_Select
     * @param Omeka_Record
     * @return void
     */
    public function filterByRecord($select, $record)
    {
        if ($record->exists()) {
            $record_id = $record->id;
            $select->where("tg.relation_id = ?", $record_id);
            
            if (empty($for)) {
                $select->where("tg.type = ?", get_class($record));
            }
        //A non-persistent record has no tags, so return emptiness
        } else {
            $select->where('t.id = 0');
        }        
    }

    /**
     * Apply custom sorting for tags.
     *
     * This also applies the normal, built-in sorting.
     *
     * @param Omeka_Db_Select $select
     * @param string $sortField Sorting field.
     * @param string $sortDir Sorting direction, suitable for direct
     *  inclusion in SQL (ASC or DESC).
     */
    public function applySorting($select, $sortField, $sortDir)
    {
        parent::applySorting($select, $sortField, $sortDir);

        switch ($sortField) {
            case 'time':
                $select->order(array("tg.time $sortDir", 't.name ASC'));
                break;
            case 'count':
                $select->order("tagCount $sortDir");
                break;
            default:
                break;
        }
    }
    
    /**
     * Filter SELECT statement based on the type of tags to view (Item, Exhibit, etc.)
     * 
     * @param Omeka_Db_Select
     * @param string
     * @return void
     */
    public function filterByTagType($select, $type)
    {
        $db = $this->getDb();
        
        //Showing tags related to items
        if ($type == 'Item') {
            //Join on the items table, add permissions checks for public
            $select->joinInner( array('i'=>$db->Item), "i.id = tg.relation_id AND tg.type = 'Item'", array());
            if($acl = Omeka_Context::getInstance()->getAcl()) {
                new ItemPermissions($select, $acl);
            }
        } else {
            $select->where("tg.type = ?", (string) $type);
        }
    }
    
    /**
     * Filter SELECT statement based on whether the tag contains the partial tag name
     * 
     * @param Omeka_Db_Select
     * @param string
     * @return void
     */
    public function filterByTagNameLike($select, $partialTagName) 
    {
        $select->where("`t`.`name` LIKE CONCAT('%', ?, '%')", $partialTagName);
    }
         
    /**
     * Retrieve a certain number of tags
     *
     * @param Omeka_Db_Select 
     * @param array $params
     *        'limit' => integer
     *        'record' => instanceof Omeka_Record
     *        'like' => partial_tag_name
     *        'type' => tag_type
     * @return void
     */
    public function applySearchFilters($select, $params=array())
    {        
        $db = $this->getDb();
        
        if (array_key_exists('type', $params)) {
            $this->filterByTagType($select, $params['type']);
            
            //If we only want tags for public items, use one of the ItemTable's filters
            if ($params['type'] == 'Item' && isset($params['public'])) {
                $db->getTable('Item')->filterByPublic($select, (bool) $params['public']);
            }
        }
        
        if (array_key_exists('record', $params) && $params['record'] instanceof Omeka_Record) {
            $this->filterByRecord($select, $params['record']);
        }

        if (array_key_exists('like', $params)) {
            $this->filterByTagNameLike($select, $params['like']);
        }
                        
        $select->group("t.id");
    }
    
        
    /**
     * @internal SELECT statements should always pull a count of how many times 
     * the tag occurs as a tagCount field in the Tag object.
     * 
     * @return Omeka_Db_Select
     */    
    public function getSelect()
    {
        $select = new Omeka_Db_Select;
        
        $db = $this->getDb();
        
        $select->from(array('t'=>$db->Tag), array('t.*', 'tagCount'=>'COUNT(t.id)'))
                ->joinInner( array('tg'=>$db->Taggings), "tg.tag_id = t.id", array())
                ->group('t.id');
                
        return $select;
    }
    
    public function findTagNamesLike($partialName, $limit = 10)
    {
        $db = $this->getDb();
        $sql = "SELECT t.name FROM $db->Tag t WHERE t.name LIKE ? LIMIT $limit";
        $tags = $db->fetchCol($sql, array($partialName . '%'));
        return $tags;
    }
}
