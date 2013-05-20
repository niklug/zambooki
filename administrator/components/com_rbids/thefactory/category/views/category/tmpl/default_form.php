<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action = "index.php?option=<?php echo APP_EXTENSION;?>" method = "post" name = "adminForm">
    <input type = "hidden" name = "task" value = "category.savecat" />
    <input type = "hidden" name = "cid" value = "<?php echo $this->row->id;?>" />
    <input type = "hidden" name = "ordering" value = "<?php echo $this->row->ordering;?>" />
    <table class = "adminForm" align = "left">
        <tr valign = "top">
            <td width = "10%"><?php echo JText::_('FACTORY_NAME');?>:&nbsp;</td>
            <td>
                <input type = "text"
                       name = "catname"
                       size = "41"
                       style = "font-size: 12px;"
                       value = "<?php echo $this->row->catname;?>"
                        />
            </td>
        </tr>
        <tr valign = "top">
            <td width = "10%"><?php echo JText::_('FACTORY_DESCRIPTION');?>:&nbsp;</td>
            <td>
                <textarea name = "description" cols = "30" rows = "10"><?php echo $this->row->description;?></textarea>
            </td>
        </tr>
        <tr valign = "top">
            <td width = "10%"><?php echo JText::_('FACTORY_PARENT');?>:&nbsp;</td>
            <td>
		    <?php echo JHtml::_('factorycategory.select', 'parent', '', $this->row->parent, true, false, true, JText::_('FACTORY_ROOT_CATEGORY'));?>
            </td>
        </tr>
    </table>
</form>

