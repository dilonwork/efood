<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>1030 管理後台</title>
    <link href="<?php echo base_url('assets/inspinia_plugins/css/bootstrap.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/inspinia_plugins/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/inspinia_plugins/css/animate.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/inspinia_plugins/css/plugins/sweetalert/sweetalert.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/inspinia_plugins/css/style.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/inspinia_plugins/css/plugins/iCheck/blue.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/inspinia_plugins/css/plugins/select2/select2.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/Site.css'); ?>" rel="stylesheet">

    <script src="<?php echo base_url('assets/inspinia_plugins/js/jquery-3.1.1.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/inspinia_plugins/js/bootstrap.min.js'); ?>"></script>

    <!--
    <style type="text/css">

    ::selection { background-color: #E13300; color: white; }
    ::-moz-selection { background-color: #E13300; color: white; }

    body {
        background-color: #FFF;
        margin: 40px;
        font: 16px/20px normal Helvetica, Arial, sans-serif;
        color: #4F5155;
        word-wrap: break-word;
    }

    a {
        color: #003399;
        background-color: transparent;
        font-weight: normal;
    }

    h1 {
        color: #444;
        background-color: transparent;
        border-bottom: 1px solid #D0D0D0;
        font-size: 24px;
        font-weight: normal;
        margin: 0 0 14px 0;
        padding: 14px 15px 10px 15px;
    }

    code {
        font-family: Consolas, Monaco, Courier New, Courier, monospace;
        font-size: 16px;
        background-color: #f9f9f9;
        border: 1px solid #D0D0D0;
        color: #002166;
        display: block;
        margin: 14px 0 14px 0;
        padding: 12px 10px 12px 10px;
    }

    #body {
        margin: 0 15px 0 15px;
    }

    p.footer {
        text-align: right;
        font-size: 16px;
        border-top: 1px solid #D0D0D0;
        line-height: 32px;
        padding: 0 10px 0 10px;
        margin: 20px 0 0 0;
    }

    #container {
        margin: 10px;
        border: 1px solid #D0D0D0;
        box-shadow: 0 0 8px #D0D0D0;
    }
    </style>
    -->
</head>
<body>
    <h1>1030 管理後台 </h1>
    <div class="ibox">
        <div class="ibox-title">
            <h5>LOGIN</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-wrench"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="#" class="dropdown-item">Config option 1</a>
                    </li>
                    <li><a href="#" class="dropdown-item">Config option 2</a>
                    </li>
                </ul>
                <a class="close-link">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-sm-6 b-r"><h3 class="m-t-none m-b">Sign in</h3>
                    <p>Sign in today for more expirience.</p>
                    <form action="<?php echo site_url('site/console/login')?>" method="post">
                        <div class="form-group"><label>Account</label> <input name='account' type="text" placeholder="Account" class="form-control"></div>
                        <div class="form-group"><label>Password</label> <input name="password" type="password" placeholder="Password" class="form-control"></div>
                        <div>
                            <button id="btnLogin" class="btn btn-sm btn-primary float-right m-t-n-xs" type="submit"><strong>Log in</strong></button>
                            <label class=""> <div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" class="i-checks" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div> Remember me </label>
                        </div>
                    </form>
                </div>
                <div class="col-sm-6"><h4>Not a member?</h4>
                    <p>You can create an account:</p>
                    <p class="text-center">
                        <a href=""><i class="fa fa-sign-in big-icon"></i></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

    $(function ()
    {
        console.log('Loading...');

        // $('#btnLogin').on('click', function ()
        // {
        //     console.log("redirect to <?php echo site_url('site/console'); ?>");
        //     window.location.href ="<?php echo site_url('site/console'); ?>";                
        // });
    });

    </script>

<!--
<div id="container">
    <h1>Welcome to CodeIgniter!</h1>

    <div id="body">
        <?php echo base_url('user_guide/index.html'); ?>

        <h2><a href="<?php echo site_url('rest-server'); ?>">REST Server Tests</a></h2>

        <?php if (file_exists(FCPATH.'documentation/index.html')) : ?>
        <h2><a href="<?php echo base_url('documentation/index.html'); ?>" target="_blank">REST Server Documentation</a></h2>
        <?php endif ?>

        <p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

        <p>If you would like to edit this page you'll find it located at:</p>
        <code>application/views/welcome_message.php</code>

        <p>The corresponding controller for this page is found at:</p>
        <code>application/controllers/Welcome.php</code>

        <?php if (file_exists(FCPATH.'user_guide/index.html')) : ?>
        <p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="<?php echo base_url('user_guide/index.html'); ?>" target="_blank">User Guide</a>.</p>
        <?php endif ?>
    </div>

    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>'.CI_VERSION.'</strong>' : '' ?></p>
</div>
-->
</body>
</html>
