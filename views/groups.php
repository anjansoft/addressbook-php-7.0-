<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
</head> 
<body> 
    <div class="container"> 
        <header> 
            <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL;?>assets/css/bootstrap.min.css"> 
            <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL;?>assets/css/dataTables.bootstrap4.min.css"> 
            <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL;?>assets/css/style.css"> 
            <link href="<?php echo BASE_URL;?>assets/css/css.css" rel="stylesheet">
            <link rel="stylesheet" href="<?php echo BASE_URL;?>assets/css/icon.css">
            <link rel="stylesheet" href="<?php echo BASE_URL;?>assets/css/font-awesome.min.css"> 
 
            <script src="<?php echo BASE_URL;?>assets/js/jquery-3.3.1.js"></script>  
            <script src="<?php echo BASE_URL;?>assets/js/jquery.dataTables.min.js"></script>
            <script src="<?php echo BASE_URL;?>assets/js/popper.min.js"></script>  
            <script src="<?php echo BASE_URL;?>assets/js/dataTables.bootstrap4.min.js"></script> 
            <script src="<?php echo BASE_URL;?>assets/js/bootstrap.min.js"></script>    
        </header>  
         <!-- Navigation Bar -->
         <nav class="navbar navbar-expand-lg navbar-light bg-light"> 
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
            <a class="nav-item nav-link" href="<?php echo BASE_URL;?>index.php">Contacts</a>
            <a class="nav-item nav-link active" href="<?php echo BASE_URL;?>index.php?c=group&m=index">Groups</a> 
            </div>
            </div>
        </nav>  
        <!-- group header -->
        <section> 
            <div class="row">
                <div class="col-lg-6">
                    <button type="button" id="addgroup" class="btn btn-primary" data-toggle="modal">Add group</button> 
                </div> 
 
            </div>
            
        </section>  
        <!-- group Data Table HTML -->
        <section>
            <table id="groupTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                       <th>ID</th>
                        <th>Group Name</th> 
                        <th class="no-sort">#</th>
                        <th class="no-sort">#</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php   
                    foreach ($data['groups'] as $row){  
                    ?>
                    <tr>
                    <td><?php echo $row['group_id']; ?></td>
                        <td><a href="<?php echo BASE_URL;?>index.php?c=contact&m=filterContacts&group_id=<?php echo $row['group_id']; ?>"><?php echo $row['group_name']; ?></a></td> 
                        <td><a href="javascript:void(0)" class="lnkEdit"  data-id="<?php echo $row['group_id']; ?>"><i title="Edit group" class="material-icons"></i>Edit / InheritContacts</a></td>
                        <td><a href="javascript:void(0)" class="lnkDelete"  data-id="<?php echo $row['group_id']; ?>"><i title="Delete group" class="material-icons"></i>Delete</a></td>
                    </tr> 
                    <?php }?>
                </tbody> 
            </table>
        </section>  

        <footer>
            <!-- Add group Modal HTML -->
            <div class="modal" id="groupModal">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><span id="group_title">Add Group</span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form id="groupform" method="post" class="subscribe-form" action="<?php echo BASE_URL;?>index.php?group/addgroup" enctype="multipart/form-data">
                            <div class="row">                 
                                <div class="col-lg-8"> 
                                    <div class="form-group"> 
                                        <input type="text" maxlength="60" autocomplete="off" id="name" name="name" placeholder="Group Name"  class="form-control"/>
                                        <span class="error_msg"></span>
                                    </div> 
                                </div> 
                            </div> 
                            <div class="row">
                                <div class="col-lg-10" style="padding-bottom:20px" >
                                    Inherit contacts from below groups
                                    <hr>
                                   <div style="padding:10px;color:green"> Please press <b>CNTRL</b> to select multiple groups.</div>
                                    
                                    <select  name="in_group[]" id="in_group" multiple size = 6 width="100px" class="custom-select">
                                     <option value="0">--- NONE ---</option>
                                     <?php  foreach ($data['groups'] as $row){  ?>
                                    <option value="<?php echo $row['group_id'];?>"> <?php echo $row['group_name'];?></option>
                                  <?php } ?>
                                   </select>
                                </div>
                              
			                </div>
                            <div class="form-group">
                                <input name="baseURL" id="baseURL" type="hidden" value='<?php echo BASE_URL;?>'>
                                <span class="form-control_other">  
                                <button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                                </span>    
                            </div>  
                            <input type="hidden" name="group_id" id="group_id">
                        </form>  
                    </div>  
                    </div>
                </div>
            </div>  
          

             <!-- Delete Confirmation Modal HTML -->
             <div id="deleteModal" class="modal fade">
                <div class="modal-dialog modal-confirm">
                    <div class="modal-content"> 
                        <div class="modal-body">
                            <div class="icon-box">
                            <i class="material-icons">delete</i>
                            </div>
                            <h6 class="modal-title">Are you sure?Do you really want to delete group.</h4>	
                            <input type="hidden" name="delete_group_id" id="delete_group_id">
                        </div>
                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-danger" name="btnDelete" id="btnDelete">Delete</button>
                            <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <script src="<?php echo BASE_URL;?>assets/js/group-1.2.js"></script> 
        </footer> 
    </div> 
</body>
</html>
