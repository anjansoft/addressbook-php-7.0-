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
            <a class="nav-item nav-link active" href="<?php echo BASE_URL;?>index.php">Contacts</a>
            <a class="nav-item nav-link" href="<?php echo BASE_URL;?>index.php?c=group&m=index">Groups</a> 
            </div>
            </div>
        </nav> 
       
        <!-- Contact header -->
        <section>
            <div class="row"> 
                <div class="col-lg-2">
                    <button type="button" id="addContact" class="btn btn-primary" data-toggle="modal">Add Contact</button> 
                </div> 
                <div class="col-lg-3">
                   <select  id="groupFilter"  class="custom-select">
                   <option value="0" <?php if ($data['input']==0) echo "selected";?>>--- All Contacts---</option>
                    <?php  foreach ($data['groups'] as $row){  ?>
                    <option value="<?php echo $row['group_id'];?>" <?php if ($data['input']==$row['group_id']) echo "selected";?> >
                    
                     <?php echo $row['group_name'];?></option>
                    <?php } ?>
                    </select>
                </div> 

                <div class="col-lg-3">
                    <select  id="tag_id" name="tag_id" class="custom-select">
                    <option value="-1" <?php if ($data['tag']==-1) echo "selected";?>>---SELECT---</option>
                    <option value="0" <?php if ($data['tag']==0) echo "selected";?>>All Tagged Contacts</option>
                    <?php  foreach ($data['tags'] as $tag){  ?>
                    <option value="<?php echo $tag['id'];?>" <?php if ($data['tag']==$tag['id']) echo "selected";?>> <?php echo $tag['tag_name'];?></option>
                    <?php } ?>
                    </select>
                </div> 

                <div class="col-lg-4" style="text-align:right"> 
                <a href="#XMLModal" class="trigger-btn" data-toggle="modal">
                 <img src="<?php echo BASE_URL;?>assets/images/xml.png"  title="Download XML"></a>&nbsp;&nbsp;&nbsp; &nbsp;
                 <a href="#JSONModal" class="trigger-btn" data-toggle="modal"> 
                      <img src="<?php echo BASE_URL;?>assets/images/json.png" title="Download JSON"></a>
                </div>
            </div>
            
        </section>  
       
        <!-- Contact Data Table HTML -->
        <section> 
            <table id="contactTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                       <th>ID</th> 
                        <th>Name</th>
                        <th>FirstName</th>
                        <th>Email</th>
                        <th>Street</th>
                        <th>City</th>
                        <th>Zipcode</th>  
                        <th class="no-sort">#</th>
                        <th class="no-sort">#</th>
                        <th class="no-sort">#</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php   
                    foreach ($data['contacts'] as $row){  
                    ?>
                    <tr>
                    <td><?php echo $row['contact_id']; ?></td> 
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['first_name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['street']; ?></td>
                        <td><?php echo $row['city']; ?></td>
                        <td><?php echo $row['zipcode']; ?></td> 
                        <td><a href="javascript:void(0)" class="lnkTag"  data-id="<?php echo $row['contact_id']; ?>"> <button class="btn btn-primary btn-sm" style="padding-top:0px;padding-bottom:0px;" >TAG</button></a></td>
                        <td><a href="javascript:void(0)" class="lnkEdit"  data-id="<?php echo $row['contact_id']; ?>"><i title="Edit Contact" class="material-icons">edit</i></a></td>
                        <td><a href="javascript:void(0)" class="lnkDelete" data-gid="<?php echo $row['group_id']; ?>" data-id="<?php echo $row['contact_id']; ?>"><i title="Delete Contact" class="material-icons">delete</i></a></td>
                    </tr> 
                    <?php }?>
                </tbody> 
            </table>
        </section> 
        
        <footer>
            <!-- Add Contact Modal HTML -->
            <div class="modal" id="contactModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><span id="contact_title">Add Contact</span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form id="contactform" method="post" class="subscribe-form" action="<?php echo BASE_URL;?>index.php?c=contact&m=addContact" enctype="multipart/form-data">
                            <div class="row">                 
                                <div class="col-lg-6"> 
                                    <div class="form-group">
                                        <label for="city">Group</label> 
                                        <select name="group" id="group" width="100px" class="custom-select">
                                            <option value="default" selected>Select Group</option>
                                            <?php foreach ($data['groups'] as $row){ ?>
                                            <option value="<?php echo $row['group_id'];?>"><?php echo $row['group_name'];?> </option>
                                            <?php } ?> 
                                            </select>
                                        <span class="error_msg"></span>
                                    </div> 
                                </div>
                                <div class="col-lg-6"> 
                                  
                                </div>
                            </div>

                            <div class="row">                 
                                <div class="col-lg-6"> 
                                    <div class="form-group">
                                        <label for="name">Name</label> 
                                        <input type="text" maxlength="60" id="name" name="name" placeholder="Name"  class="form-control"/>
                                        <span class="error_msg"></span>
                                    </div> 
                                </div>
                                <div class="col-lg-6"> 
                                    <div class="form-group">
                                        <label for="firstName">First Name</label> 
                                        <input type="text" id="firstName" maxlength="60"  name="firstName" placeholder="First Name"  class="form-control"/>
                                        <span class="error_msg"></span>
                                    </div> 
                                </div>
                            </div>

                            <div class="row">                 
                                <div class="col-lg-6"> 
                                    <div class="form-group">
                                        <label for="email">Email</label> 
                                        <input type="text" maxlength="90" id="email" name="email" placeholder="Email"  class="form-control"/>
                                        <span class="error_msg"></span>
                                    </div> 
                                </div>
                                <div class="col-lg-6"> 
                                    <div class="form-group">
                                        <label for="street">Street</label> 
                                        <input type="text" maxlength="150"  id="street" name="street" placeholder="Street"  class="form-control"/>
                                        <span class="error_msg"></span>
                                    </div> 
                                </div>
                            </div>

                            <div class="row">                 
                                <div class="col-lg-6"> 
                                    <div class="form-group">
                                        <label for="city">CIty</label> 
                                        <select name="city" id="city" width="100px" class="custom-select">
                                            <option value="default" selected>Select City</option>
                                            <?php foreach ($data['city'] as $row){ ?>
                                            <option value="<?php echo $row['city_id'];?>"><?php echo $row['name'];?> </option>
                                            <?php } ?> 
                                            </select>
                                        <span class="error_msg"></span>
                                    </div> 
                                </div>
                                <div class="col-lg-6"> 
                                    <div class="form-group">
                                        <label for="zipcode">Zipcode</label> 
                                        <input type="text" maxlength="15"  id="zipcode" name="zipcode" placeholder="Zipcode"  class="form-control"/>
                                        <span class="error_msg"></span>
                                    </div> 
                                </div>
                            </div> 

                            <div class="form-group" style="float:right">
                                <input name="baseURL" id="baseURL" type="hidden" value='<?php echo BASE_URL;?>'>
                                <span class="form-control_other">  
                                <button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                                </span>    
                            </div>  
                            <input type="hidden" name="contact_id" id="contact_id">
                            <input type="hidden" name="group_id" id="group_id">
                        </form>  
                    </div>  
                    </div>
                </div>
            </div>  
            <!-- XML File Download Confirmation Modal HTML -->
            <div id="XMLModal" class="modal fade">
                <div class="modal-dialog modal-confirm">
                    <div class="modal-content"> 
                        <div class="modal-body">
                        <div class="icon-box">
                            <i class="material-icons">cloud_download</i>
                        </div>
                            <h6 class="modal-title">Are you sure?Do you really want to download contacts XML file.</h4>	
                            </div>
                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-danger" name="xmlDownload" id="xmlDownload">Download</button>
                            <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>  
            <!-- JSON File Download Confirmation Modal HTML -->
            <div id="JSONModal" class="modal fade">
                <div class="modal-dialog modal-confirm">
                    <div class="modal-content"> 
                        <div class="modal-body">
                            <div class="icon-box">
                            <i class="material-icons">cloud_download</i>
                            </div>
                            <h6 class="modal-title">Are you sure?Do you really want to download contacts JSON file.</h4>	
                        </div>
                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-danger" name="jsonDownload" id="jsonDownload">Download</button>
                            <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
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
                            <h6 class="modal-title">Are you sure?Do you really want to delete contact.</h4>	
                            <input type="hidden" name="delete_contact_id" id="delete_contact_id">
                        </div>
                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-danger" name="btnDelete" id="btnDelete">Delete</button>
                            <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

             <!-- Add Contact Modal HTML -->
             <div class="modal" id="tagModal">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><span id="contact_title">Tag Contact</span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form id="tagform" method="post" class="subscribe-form" action="<?php echo BASE_URL;?>index.php?c=contact&m=updateContactTag" enctype="multipart/form-data">
                            <div class="row">                 
                                <div class="col-lg-12"> 
                                    <div class="form-group">
                                    <div style="padding:10px;color:green"> Please press <b>CNTRL</b> to select multiple Tags.</div>
                                        <select name="ctags[]" id="ctags" style="height:200px;" multiple class="custom-select">
                                            <option value="0">--- NONE ---</option>
                                            <?php  foreach ($data['tags'] as $tag){  ?>
                                            <option value="<?php echo $tag['id'];?>"> <?php echo $tag['tag_name'];?></option>
                                            <?php } ?>
                                            </select>
                                        <span class="error_msg"></span>
                                    </div> 
                                </div> 
                            </div> 

                            <div class="form-group" style="float:right">
                                <input name="baseURL" id="baseURL" type="hidden" value='<?php echo BASE_URL;?>'>
                                <span class="form-control_other">  
                                <button type="submit" id="btnTag" name="btnTag" class="btn btn-primary">Add</button>
                                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                                </span>    
                            </div>  
                            <input type="hidden" name="tag_cid" id="tag_cid"> 
                        </form>  
                    </div>  
                    </div>
                </div>
            </div>
            <script src="<?php echo BASE_URL;?>assets/js/contact-1.2.js"></script>  
        </footer> 
    </div> 
</body>
</html>
