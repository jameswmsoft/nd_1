<?php
include_once("header.php");
include_once("left_menu.php");
?>
<div class="main-panel">
    <?php include_once('navbar.php');?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">
                                template
                                <input type="button" class="btn btn-primary" value="Add New" style="float:right !important" onclick="window.location='add_template.php'" />
                            </h4>
                            <p class="category">Already saved list of template.</p>
                        </div>
                        <div class="content table-responsive table-full-width">
                            <table id="aptTable" class="table table-hover table-striped listTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Manage</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = "select * from template where user_id='".$_SESSION['user_id']."'";
                                $res = mysqli_query($link,$sql);
                                if(mysqli_num_rows($res)){
                                    $index = 1;
                                    while($row = mysqli_fetch_assoc($res)){
                                        ?>
                                        <tr>
                                            <td><?php echo $index++?></td>
                                            <td style="text-align:left;text-align:center;"><?php echo $row['title']?></td>
                                            <td style="text-align:left;text-align:center;"><?php echo $row['content']?></td>
                                            <td style="text-align:center">
                                                <a href="edit_template.php?id=<?php echo $row['id']?>"><i class="fa fa-edit" style="color:orange"></i></a>&nbsp;&nbsp;
                                                <i class="fa fa-trash-o" style="color:red; cursor:pointer" onclick="deletetp('<?php echo $row['id']?>')"></i>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once("footer_info.php");?>
</div>
<?php include_once("footer.php");?>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script>
    function deletetp(tpID){
        if(confirm("Are you sure you want to delete this template?")){
            $.post('server.php',{tpID:tpID,"cmd":"delete_tp"},function(r){
                window.location = 'view_template.php';
            });
        }
    }
</script>