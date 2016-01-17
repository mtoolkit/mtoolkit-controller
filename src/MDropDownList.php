<?php
namespace mtoolkit\controller;

use mtoolkit\core\MDataType;
use mtoolkit\core\MList;

class MDropDownList extends MAbstractViewController
{
    /**
     * @var MDropDownListItemList
     */
    private $items;

    public function __construct( $template = null, MAbstractViewController $parent = null )
    {
        parent::__construct( $template, $parent );
        $this->items = new MDropDownListItemList($this);
    }

    /**
     * @return MDropDownListItemList Returns the items by reference.
     */
    public function &getItems()
    {
        return $this->items;
    }

    /**
     * @param MDropDownListItemList $items
     * @return MDropDownList
     */
    public function setItems( MDropDownListItemList $items )
    {
        $this->items = $items;
        return $this;
    }
    
    /**
     * Clears out the list selection and sets the <i>$selected</i> property of all items to false.
     */
    public function clearSelection()
    {
        foreach( $this->items as /* @var $value MDropDownListItem */ $value )
        {
            $value->setSelected(false);
        }
    }

    public function render()
    {
        $output = "";
        $output.="<select" . $this->renderAttributes() . ">" . PHP_EOL;
        foreach($this->items as /* @var $item MDropDownListItem */ $item )
        {
            $output.='<option value="'.$item->getKey().'"'.($item->getSelected()?' selected':'').'>'.$item->getValue().'</option>';
        }
        $output.="</select>" . PHP_EOL;

        $this->setOutput( $output );
    }

}

class MDropDownListItemList extends MList
{
    private $keys=array();
    private $value=array();
    
    public function __construct( )
    {
        parent::__construct( array(), 'MToolkit\Controller\MDropDownListItem');
    }
    
    public function append( MDropDownListItem &$item )
    {
        $this->keys[$item->getKey()]=$item;
        $this->value[$item->getValue()]=$item;
        
        parent::append($item);
    }
    
    public function &findByKey( $key )
    {
        if( array_key_exists( $key, $this->keys )===false )
        {
            return null;
        }
        
        return $this->keys[$key];
    }
    
    public function &findByValue( $value )
    {
        if( array_key_exists( $value, $this->value )===false )
        {
            return null;
        }
        
        return $this->value[$value];
    }
}

class MDropDownListItem
{
    private $key = null;
    private $value = null;
    private $selected = false;

    public function __construct($key, $value, $selected=false)
    {
        $this->key=$key;
        $this->value=$value;
        $this->selected=$selected;
    }
    
    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getSelected()
    {
        return $this->selected;
    }

    public function setKey( $key )
    {
        MDataType::mustBeString($key);
        
        $this->key = $key;
        return $this;
    }

    public function setValue( $value )
    {
        MDataType::mustBeString($value);
        
        $this->value = $value;
        return $this;
    }

    public function setSelected( $selected )
    {
        MDataType::mustBeBoolean($selected);
        
        $this->selected = $selected;
        return $this;
    }

}
