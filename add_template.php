<?php

include_once("header.php");

include_once("left_menu.php");

?>

<style>

    .delay_table tr td{

        padding:5px !important;

    }

</style>

<div class="main-panel">

    <?php include_once('navbar.php');?>

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <form method="post" enctype="multipart/form-data" action="server.php">

                    <div class="col-md-12">

                        <div class="card">

                            <div class="header">

                                <h4 class="title">

                                    Make New template

                                    <input type="button" class="btn btn-default" value="Back" style="float:right !important" onclick="window.location='view_template.php'" />

                                </h4>

                                <p class="category">Make new template from here.</p>

                            </div>

                            <div class="content table-responsive">

                                <div class="form-group">

                                    <label>Title</label>

                                    <input type="text" name="tp_title" class="form-control" />

                                </div>

                                <div class="form-group">

                                    <label>Content</label>

                                    <input type="text" name="tp_content" class="form-control" />

                                </div>

                                <div class="form-group">

                                    <input type="hidden" name="cmd" value="save_template" />

                                    <input type="submit" value="Save" class="btn btn-primary" />

                                    <input type="button" class="btn btn-default" value="Back" onclick="window.location='view_template.php'" />

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <?php include_once("footer_info.php");?>

</div>

<?php include_once("footer.php");?>
