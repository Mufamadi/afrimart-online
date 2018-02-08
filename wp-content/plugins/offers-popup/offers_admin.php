<div class="wrap">
  <h2>Your Offers <a href="admin.php?page=offers_add_offer"  class="add-new-h2">Add New Offer</a></h2>
  <?php if($_GET["info"]!=""){ ?>
  <div class="updated below-h2" id="message">
    <p>
      <?php if($_GET["info"]=="1"){  echo "Offer has been added Successfully."; }elseif($_GET["info"]=="2"){ echo "Offer has been updated Successfully."; }elseif($_GET["info"]=="3"){ echo "Offer has been deleted Successfully."; }elseif($_GET["info"]=="4"){ echo "Offer Status has been changed Successfully."; } ?>
     
    </p>
  </div>
   <?php } ?>
  <table class="wp-list-table widefat fixed offers_list">
    <thead>
      <tr>
        <th class="manage-column column-title <?php echo ( $_GET['orderby']=='' || $_GET['orderby']=='offer_name')?'sorted':'sortable';?> <?php echo $_GET['order']=='asc'?'desc':'asc' ?>" id="title" scope="col"> <a href="<?php echo admin_url('admin.php?page=offers_admin.php&orderby=offer_name&order='.($_GET['order']=='asc'?'desc':'asc')); ?>"> <span>Offer Name</span> <span class="sorting-indicator"></span> </a> </th>
        <th class="manage-column column-start-date <?php echo $_GET['orderby']=='offer_start'?'sorted':'sortable';?> <?php echo $_GET['order']=='asc'?'desc':'asc' ?>" id="title" scope="col"> <a href="<?php echo admin_url('admin.php?page=offers_admin.php&orderby=offer_start&order='.($_GET['order']=='asc'?'desc':'asc')); ?>"> <span>Start Date</span> <span class="sorting-indicator"></span> </a> </th>
        <th class="manage-column column-end-date <?php echo $_GET['orderby']=='offer_end'?'sorted':'sortable';?> <?php echo $_GET['order']=='asc'?'desc':'asc' ?>" id="title" scope="col"> <a href="<?php echo admin_url('admin.php?page=offers_admin.php&orderby=offer_end&order='.($_GET['order']=='asc'?'desc':'asc')); ?>"> <span>Start Date</span> <span class="sorting-indicator"></span> </a> </th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th class="manage-column column-title <?php echo ( $_GET['orderby']=='' || $_GET['orderby']=='offer_name')?'sorted':'sortable';?> <?php echo $_GET['order']=='asc'?'desc':'asc' ?>" id="title" scope="col"> <a href="<?php echo admin_url('admin.php?page=offers_admin.php&orderby=offer_name&order='.($_GET['order']=='asc'?'desc':'asc')); ?>"> <span>Offer Name</span> <span class="sorting-indicator"></span> </a> </th>
        <th class="manage-column column-start-date <?php echo $_GET['orderby']=='offer_start'?'sorted':'sortable';?> <?php echo $_GET['order']=='asc'?'desc':'asc' ?>" id="title" scope="col"> <a href="<?php echo admin_url('admin.php?page=offers_admin.php&orderby=offer_start&order='.($_GET['order']=='asc'?'desc':'asc')); ?>"> <span>Start Date</span> <span class="sorting-indicator"></span> </a> </th>
        <th class="manage-column column-end-date <?php echo $_GET['orderby']=='offer_end'?'sorted':'sortable';?> <?php echo $_GET['order']=='asc'?'desc':'asc' ?>" id="title" scope="col"> <a href="<?php echo admin_url('admin.php?page=offers_admin.php&orderby=offer_end&order='.($_GET['order']=='asc'?'desc':'asc')); ?>"> <span>Start Date</span> <span class="sorting-indicator"></span> </a> </th>
      </tr>
    </tfoot>
    <tbody>
      <?php if(count($offers)>0){
			$i=0;
		 foreach($offers as $o_data){ 
?>
      <tr class="<?php echo $i%2 ==0? 'alternate' : ''; $i++; ?>">
        <td><strong><a title="Click to edit details" href="<?php echo admin_url('admin.php?page=offers_add_offer&id='.$o_data->offer_id); ?>"><?php echo $o_data->offer_name; ?></a></strong>
          <div class="row-actions"> <span class="edit"><a title="Click to edit details" href="<?php echo admin_url('admin.php?page=offers_add_offer&id='.$o_data->offer_id); ?>">Edit</a> | </span> <span class="trash"><a title="Click to delete this offer" class="submitdelete" href="javascript:void(0);" onclick="return delete_page('<?php echo $o_data->offer_name; ?>', '<?php echo $o_data->offer_id; ?>')">Delete</a> | </span>
            <?php if($o_data->status == '1' ){ ?>
            <span class="view"><a rel="permalink" title="Click to Deactivate this offer" href="javascript:void(0);" onclick="return status_change('Deactivate', '0', '<?php echo $o_data->offer_name; ?>', '<?php echo $o_data->offer_id; ?>')">Deactivate</a></span>
            <?php }else{ ?>
            <span class="view"><a rel="permalink" title="Click to Activate this offer" href="javascript:void(0);" onclick="return status_change('Activate', '1', '<?php echo $o_data->offer_name; ?>', '<?php echo $o_data->offer_id; ?>')">Activate</a></span>
            <?php } ?>
          </div></td>
        <td><?php echo displaydate($o_data->offer_start); ?></td>
        <td><?php echo displaydate($o_data->offer_end); ?></td>
      </tr>
      <?php }
	}else{
?>
      <tr align="center">
        <td colspan="3">No Offer available.</td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>