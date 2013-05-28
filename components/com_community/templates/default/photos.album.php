<?php
/**
 * @package		JomSocial
 * @subpackage 	Template
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 * @param	applications	An array of applications object
 * @param	pagination		JPagination object
 */
defined('_JEXEC') or die();
?>
<style>
    .cMedia-Box div.cMedia-Avatar a.cMedia-Thumb img {
        height: 200px;
    }
    
</style>
<?php
if( $photos && $isOwner )
{
?>+
<script type="text/javascript" src="<?php echo rtrim(JURI::root(),'/'); ?>/components/com_community/assets/ui.core.js"></script>
<script type="text/javascript" src="<?php echo rtrim(JURI::root(),'/'); ?>/components/com_community/assets/ui.sortable.js"></script>
<script type='text/javascript'>
joms.jQuery(document).ready(function(){
	joms.jQuery("ul.cMedia-ThumbList").dragsort({ dragSelector: "li div.cMedia-Box .cDragControl",
  		dragEnd: function() {
  			// Get new ordering
  			var ordering = [];
  			joms.jQuery("ul.cMedia-ThumbList li").each(function(index) {
				ordering.push( 'app-list[]=' + joms.jQuery(this).data('photoid') );
			});
  			console.log(ordering);
  			jax.call('community', 'photos,ajaxSaveOrdering', ordering.join('&') , joms.jQuery('#albumid').val() );
  			return false;
  		}, placeHolderTemplate: "<li class=\"dragged\"><div class=\"cMedia-Box\" style=\"height:200px;\"></div></li>", dragBetween: false
	});
});
</script>
<?php
}
?>
<script type='text/javascript'>
// Not required in this feature page
// Script below separate from top as applies to view on own and others albums
joms.jQuery(document).ready(function(){
	joms.jQuery('.cMapLoc').remove();
});

</script>
<?php 
$allphotos  = JRequest::getVar('allphotos');
$search_photos_all = JRequest::getVar('search_photos_all');
if($allphotos) {?>
    <form action="" method="post" name="adminForm" id="adminForm">
        <div style=" margin-bottom: 30px;  position: relative;  text-align: right;" class="">
            <a style="background-image: url('/components/com_community/templates/default/images/toogle_button_of.png');
                background-repeat: no-repeat;
                display: block;
                height: 50px;
                width: 200px;
                float:left" 
               href="index.php?option=com_community&view=photos&resetallphotos=1"
               title="Turn Album View On/Off">
            </a>
           <input type="text" name="search_photos_all" id="filter_search" placeholder="<?php echo JText::_('Search Photos'); ?>" value="<?php echo $search_photos_all?>" title="<?php echo JText::_('Search Photos'); ?>" />
            <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('Search'); ?>"><i class="icon-search"></i></button>
            <button class="btn hasTooltip" type="button" title="<?php echo JText::_('Clear'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
        </div>
    </form>
<?php 
}
?>

<div class="cLayout cPhotos-Album">

	<div class="cPageActions cPageAction clearfix">
		<?php echo $bookmarksHTML;?>
	</div>


	<?php
	if($photos)
	{
	?>
	<ul class="cMedia-ThumbList Photos cDragable cResetList cFloatedList clearfix">
	<?php
		$i = 0;
		for( $j=0; $j<count($photos); $j++ )
		{
			$row =& $photos[$j];
	?>
			<li id="photo-<?php echo $j;?>" title="<?php echo $this->escape($row->caption);?>" data-photoid="<?php echo $row->id;?>">
			<div class="cMedia-Box">
				<div class="cMedia-Avatar">
					<a href="<?php echo $row->link;?>" class="cMedia-Thumb cPhoto-Thumb">
						<img src="<?php echo $row->getThumbURI();?>" alt="<?php echo $this->escape($row->caption);?>" id="photoid-<?php echo $row->id;?>" />
                                                <br/>
                                                <?php
                                                $title = $this->escape($row->caption);
                                                $title_find = array('-' , '_');
                                                $title_replace = array(' ', ' ');
                                                $title = ucfirst((str_replace($title_find, $title_replace, $title)));
                                                $title = preg_replace("/<[^>]*>/", " ", $title);
                                                $user = &JFactory::getUser($row->creator);
                                                
                                                ?>
                                                <span style="text-align:center;"><?php echo $title;?></span>
					</a>
                                        <div class="cMedia-Details small">
                                                <?php 
                                              
                                                echo JText::_('COM_COMMUNITY_PHOTOS_BY'); ?>
                                                <a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$row->creator); ?>"><?php echo $user->name; ?></a>
                                            
                                        </div>
					<?php
					if( $isOwner )
					{
					?>
					<div class="cMedia-Drag">
						<b class="cButton cDragControl"><i class="com-icon-move"></i></b>
					</div>
					<div class="cMedia-Controls small">
						<a class="cButton cButton-Small cButton-Blue" href="javascript:void(0);" title="<?php echo JText::_('COM_COMMUNITY_REMOVE');?>" onclick="joms.gallery.confirmRemovePhoto('<?php echo $row->id;?>');">
							<?php echo JText::_('COM_COMMUNITY_REMOVE');?>
						</a>
					</div>
					<?php } ?>
				</div>
			</div>
		</li>
	<?php
		$i++;
		if( $i % 4 == 0 ) {
			$i = 0; ?>
		<div class="clearfix"></div>
		<?php }
		}?>

	</ul>
	<?php if(isset($pagination)){?>
		<div class="cPagination">
			<?php echo $pagination->getPagesLinks(); ?>
		</div>
		<?php }?>
	<?php
		}
		else
		{
	?>
		<div class="cEmpty cAlert"><?php echo JText::_('COM_COMMUNITY_PHOTOS_NO_PHOTOS_UPLOADED');?></div>
	<?php
		}
	?>
        <?php 
        // 
        $allphotos  = JRequest::getVar('allphotos');
        if(!$allphotos) {
        ?>
	<div class="cLayout cMedia-Respond">
		<div class="cSidebar">
			<?php if($photosmapdefault==0 && $zoomableMap){ ?>
			<div class="cModule app-box">
				<div id="album-map">
					<h3 class="app-box-header cResetH"><?php echo JText::sprintf('COM_COMMUNITY_PHOTOS_ALBUM_TAKEN_AT_DESC', ''); ?></h3>
					<?php echo $zoomableMap;?>
				</div>
			</div>
			<?php } ?>

			<!-- Other Album Section -->
			<!-- Other Group Album -->
			<?php if(!empty($groupId)) { ?>
			<?php if (!empty($otherGroupAlbums)) {?>
			<div class="cModule app-box">
				<h3 class="app-box-header cResetH"><?php echo JText::_('COM_COMMUNITY_GROUPS_OTHER_ALBUMS'); ?></h3>
				<div class="app-box-content">
					<ul class="cThumbDetails cResetList">
						<?php
						foreach($otherGroupAlbums as $others) { ?>
						<li>
							<a class="cThumb-Avatar cFloat-L" href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=album&albumid=' . $others->id . '&userid=' . $others->creator). '&groupid=' . $groupId; ?>">
								<img class="cAvatar" src="<?php echo $others->getCoverThumbURI(); ?>" alt="<?php echo $this->escape($others->name);?>" data="album_prop_<?php echo rand(0,200).'_'.$others->id;?>" />
							</a>
							<div class="cThumb-Detail">
								<a class="cThumb-Title" href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=album&albumid=' . $others->id . '&userid=' . $others->creator. '&groupid=' . $groupId); ?>"><?php echo $this->escape($others->name); ?></a>
								<div class="cThumb-Brief small">
									<?php if(CStringHelper::isPlural($others->count)) {
										echo JText::sprintf('COM_COMMUNITY_PHOTOS_COUNT', $others->count );
										} else {
										echo JText::sprintf('COM_COMMUNITY_PHOTOS_COUNT_SINGULAR', $others->count );
										} ?>
								</div>
							</div>
							<div class="clear"></div>
						</li>
						<?php } //end foreach ?>
					</ul>
				</div>
			</div>
			<?php } //end if ?>
			<?php } else { ?>
			<!-- Other Album Section -->
			<?php
			if (!empty($otherAlbums)) {
			?>
			<div class="cModule app-box">
				<h3 class="app-box-header"><?php echo JText::_('COM_COMMUNITY_PHOTOS_OTHER_ALBUMS');?></h3>
				<div class="app-box-content">
					<ul class="cThumbDetails cResetList">
						<?php
						foreach($otherAlbums as $others) { ?>
						<li>
							<a class="cThumb-Avatar cFloat-L" href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=album&albumid=' . $others->id . '&userid=' . $others->creator); ?>">
								<img class="cAvatar" src="<?php echo $others->getCoverThumbURI(); ?>" alt="<?php echo $this->escape($others->name);?>" data="album_prop_<?php echo rand(0,200).'_'.$others->id;?>" width="50" height="50"/>
							</a>
							<div class="cThumb-Detail">
								<a class="cThumb-Title" href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=album&albumid=' . $others->id . '&userid=' . $others->creator); ?>">
									<?php echo $this->escape($others->name); ?>
								</a>
								<div class="cThumb-Brief small">
									<?php if(CStringHelper::isPlural($others->count)) {
										echo JText::sprintf('COM_COMMUNITY_PHOTOS_COUNT', $others->count );
										} else {
										echo JText::sprintf('COM_COMMUNITY_PHOTOS_COUNT_SINGULAR', $others->count );
										} ?>
								</div>
							</div>
						</li>
						<?php } //end foreach ?>
					</ul>
				</div>
			</div>
			<?php } //end if ?>
			<?php } ?>

		</div>
		<div class="cMain">
			<div class="cMedia-Meta clearfull">
				<!-- Photo Description Section -->
				<?php
				if( ( $isOwner || $isAdmin ) || !empty($album->description) )
				{
				?>
				<div class="cMeta-Desc">
					<strong><?php echo JText::_('COM_COMMUNITY_PHOTOS_ALBUM_DESC');?></strong>
					<textarea class="community-photo-desc-editable <?php echo ( $isOwner || $isAdmin ) ? 'editable' : 'readonly';?>" <?php echo ( $isOwner || $isAdmin ) ? '' : 'readonly disabled="disabled"';?> style="border:medium none; resize:none;"><?php echo (($isOwner || $isAdmin) && empty($album->description)) ? JText::_('COM_COMMUNITY_PHOTOS_SHOW_EDITOR') : $this->escape($album->description); ?></textarea>
				</div>
				<?php
				}
				?>

				<?php if ($people): ?>
				<div class="cMedia-TagPeople">
					<strong><?php echo JText::_('COM_COMMUNITY_PHOTOS_IN_THIS_ALBUM'); ?> </strong>
						<?php $totalpeople = sizeof($people); $count = 1;
						foreach($people as $peep):?>
							<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid=' . $peep->id); ?>" rel="nofollow"><?php echo $peep->getDisplayName(); ?><?php if($count<$totalpeople){ echo ","; } ?></a>
						<?php
						$count++;
						endforeach;
						?>
				</div>
				<?php endif; ?>

				<a href="<?php echo CUrlHelper::userLink($owner->id); ?>" class="cMeta-Avatar cFloat-L">
					<img src="<?php echo $owner->getThumbAvatar(); ?>" class="cAvatar" alt="<?php echo $owner->getDisplayName(); ?>" />
				</a>

				<div class="cMeta-Details">
					<div class="cMeta-Like cFloat-R">
						<div id="like-container"><?php echo $likesHTML; ?></div>
					</div>
					<div class="cMedia-Updated">
						<?php echo JText::_('COM_COMMUNITY_BY').' <a href="'.CUrlHelper::userLink($owner->id). '">'. $owner->getDisplayName() . '</a>';?>
						<?php echo ' . '.JText::sprintf('COM_COMMUNITY_PHOTOS_ALBUM_LAST_UPDATED', $album->lastUpdated);?>
					</div>

					<?php if (!empty($album->location)): ?>
					<div class="cMedia-Location">
						<?php echo JText::sprintf('COM_COMMUNITY_PHOTOS_ALBUM_TAKEN_AT_DESC', '<a class="album-map-link" href="javascript:void(0);" onclick="joms.jQuery(\'#album-map\').toggle();">'.$album->location.'</a>');?>
					</div>
					<?php endif ?>
				</div>
			</div>

			<div class="cMedia-Comments">
				<a name="comments"></a>
				<div class="cWall-Header"><?php echo JText::_('COM_COMMUNITY_COMMENTS') ?></div>
				<div id="wallForm" class="cWall-Form"><?php echo $wallForm; ?></div>
				<div id="wallContent" class="cWall-Content"><?php echo $wallContent; ?></div>
			</div>
		</div>
	</div>
        <?php
        }
        ?>

	<input type="hidden" name="albumid" value="<?php echo $album->id;?>" id="albumid" />
</div>

<script type="text/javascript">
	joms.jQuery(document).ready(function() {
		var photoAlbumDesc = joms.jQuery('.community-photo-desc-editable');


		if (photoAlbumDesc.hasClass('editable'))
		{
			photoAlbumDesc
				.stretchToFit()
				.autogrow({lineHeight: 0, minHeight: 0})
				.focus(function()
				 {
					photoAlbumDesc
						.addClass('editing')
						.stretchToFit()
						.data('oldPhotoCaption', photoAlbumDesc.val());

					if ( photoAlbumDesc.val() == '<?php echo addslashes(JText::_('COM_COMMUNITY_PHOTOS_SHOW_EDITOR'));?>')
					{
						photoAlbumDesc.val('');
					}
				 })
				.blur(function()
				 {
					photoAlbumDesc
						.removeClass('editing')
						.stretchToFit();

					var oldPhotoCaption = joms.jQuery.trim(photoAlbumDesc.data('oldPhotoCaption'));
					var newPhotoCaption = joms.jQuery.trim(photoAlbumDesc.val());

					if (newPhotoCaption=='' || newPhotoCaption==oldPhotoCaption)
					{
						photoAlbumDesc
							.val(oldPhotoCaption)
							.trigger('autogrow');
						return;
					}

					jax.call('community', 'photos,ajaxSaveAlbumDesc', joms.jQuery('#albumid').val(), newPhotoCaption);
				 });
		}
	});
</script>
