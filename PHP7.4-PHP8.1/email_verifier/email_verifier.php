<?php

if(!defined("WHMCS")) {
    exit("This file cannot be accessed directly");
}
function email_verifier_license($licensekey = "")
{
    $results["status"] = "Active";
    if(strtolower($results["status"]) == "active") {
        $results["licensestatus"] = "License is active";
        $results["labeltype"] = "success";
    } else {
        $results["labeltype"] = "danger";
    }

    return $results;
}
function email_verifier_config()
{
    return ["name" => "Email Verifier", "description" => "Email Verifier module that optimizes email verification processes, offering enhanced security and reliability by validating user email address and ensuring accurate communication between clients and your WHMCS.", "version" => "1.1.0", "author" => "<a href='https://99modules.com/' target='_blank'>99modules</a>", "language" => "english", "fields" => ["nodeletedb" => ["FriendlyName" => "Database Table", "Type" => "yesno", "Size" => "25", "Description" => "Tick this box to delete the tables from the database when deactivating the module."],]];
}
function email_verifier_activate()
{
    if(!Illuminate\Database\Capsule\Manager::schema()->hasTable("nnm_email_verifier")) {
        Illuminate\Database\Capsule\Manager::schema()->create("nnm_email_verifier", function ($table) {
            $table->increments("id");
            $table->bigInteger("client_id");
            $table->string("email")->nullable();
            $table->string("code")->nullable();
            $table->dateTime("created_at");
        });
    }
    if(!Illuminate\Database\Capsule\Manager::schema()->hasTable("nnm_email_verifier_rq")) {
        Illuminate\Database\Capsule\Manager::schema()->create("nnm_email_verifier_rq", function ($table) {
            $table->increments("id");
            $table->bigInteger("client_id");
            $table->string("code")->nullable();
        });
    }
    if(!Illuminate\Database\Capsule\Manager::schema()->hasTable("nnm_email_verifier_bl")) {
        Illuminate\Database\Capsule\Manager::schema()->create("nnm_email_verifier_bl", function ($table) {
            $table->increments("id");
            $table->string("email")->nullable();
            $table->string("ip")->nullable();
            $table->dateTime("created_at");
            $table->dateTime("banned_until");
        });
    }
    if(!Illuminate\Database\Capsule\Manager::schema()->hasTable("nnm_email_verifier_settings")) {
        Illuminate\Database\Capsule\Manager::schema()->create("nnm_email_verifier_settings", function ($table) {
            $table->increments("id");
            $table->string("setting")->nullable();
            $table->text("value")->nullable();
        });
    }
    if(!Illuminate\Database\Capsule\Manager::table("tblemailtemplates")->where("name", "Client Email Verifier")->count()) {
        Illuminate\Database\Capsule\Manager::table("tblemailtemplates")->insert(["type" => "general", "name" => "Client Email Verifier", "subject" => "{\$company_name} - Verify Your Email", "message" => "<div style=\"max-width: 600px; margin: 0 auto; background-color: #fff; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); padding: 30px;\">\r\n<p>Dear {\$client_name},</p>\r\n<p>Thank you for your interest in purchasing our products.</p>\r\n<p>To ensure the security of your account and complete your registration, please use the following pin to verify your email address:</p>\r\n<p style=\"font-size: 18px; margin-top: 20px; text-align: center; background-color: #e1e1e1; padding: 15px;\"><strong style=\"color: #333;\">{\$email_verifier_security_code}</strong></p>\r\n<p>You are receiving this email because you recently attempted to place an order and it is necessary to have a verified email address to proceed. If you did not initiate this action, please contact our support team immediately.</p>\r\n<p>If you have any questions or need further assistance, feel free to reach out to our dedicated support team.</p>\r\n<p style=\"margin-top: 30px; text-align: center; color: #888;\">Best regards,<br />Support Team</p>\r\n</div>", "language" => "", "plaintext" => "0", "custom" => "0", "disabled" => "0"]);
    }
    if(!Illuminate\Database\Capsule\Manager::table("tblemailtemplates")->where("name", "Client Email Verifier Link")->count()) {
        Illuminate\Database\Capsule\Manager::table("tblemailtemplates")->insert(["type" => "general", "name" => "Client Email Verifier Link", "subject" => "{\$company_name} - Verify Your Email", "message" => "<div style=\"max-width: 600px; margin: 0 auto; background-color: #fff; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); padding: 30px;\">\r\n<p>Dear {\$client_name},</p>\r\n<p>Thank you for your interest in purchasing our products.</p>\r\n<p>To ensure the security of your account and complete your registration, please click on the following link to verify your email address:</p>\r\n<p style=\"margin-top: 20px; text-align: center;\"><a style=\"display: inline-block; margin-bottom: 0; font-weight: 400; text-align: center; white-space: nowrap; vertical-align: middle; -ms-touch-action: manipulation; touch-action: manipulation; cursor: pointer; background-image: none; border: 1px solid transparent; color: #fff; background-color: #337ab7; border-color: #2e6da4; padding: 10px; border-radius: 4px;\" href=\"{\$email_verifier_link}\">Verify Email</a></p>\r\n<p>Verification Link:<br />{\$email_verifier_link}</p>\r\n<p>You are receiving this email because you recently attempted to place an order, and it is necessary to have a verified email address to proceed. If you did not initiate this action, please contact our support team immediately.</p>\r\n<p>If you have any questions or need further assistance, feel free to reach out to our dedicated support team.</p>\r\n<p style=\"margin-top: 30px; text-align: center; color: #888;\">Best regards,<br />Support Team</p>\r\n</div>", "language" => "", "plaintext" => "0", "custom" => "0", "disabled" => "0"]);
    }
    return ["status" => "success", "description" => "Email Verifier has been activated."];
}
function email_verifier_deactivate()
{
    $delete = Illuminate\Database\Capsule\Manager::table("tbladdonmodules")->where("module", "email_verifier")->where("setting", "nodeletedb")->first();
    if($delete->value) {
        Illuminate\Database\Capsule\Manager::schema()->dropIfExists("nnm_email_verifier");
        Illuminate\Database\Capsule\Manager::schema()->dropIfExists("nnm_email_verifier_settings");
        Illuminate\Database\Capsule\Manager::schema()->dropIfExists("nnm_email_verifier_bl");
        Illuminate\Database\Capsule\Manager::schema()->dropIfExists("nnm_email_verifier_rq");
        Illuminate\Database\Capsule\Manager::table("tblemailtemplates")->where("name", "Client Email Verifier")->delete();
        Illuminate\Database\Capsule\Manager::table("tblemailtemplates")->where("name", "Client Email Verifier Link")->delete();
    }
    return ["status" => "success", "description" => "Email Verifier has been deactivated."];
}
function email_verifier_output($vars)
{
    $licensestatus = email_verifier_license();
    switch ($licensestatus["status"]) {
        case "Active":
            if(!class_exists("NNM_Page_Builder")) {
                include __DIR__ . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "pagebuilder.php";
            }
            $LANG = $vars["_lang"];
            $page_manager = new NNM_Page_Builder();
            $page_manager->modulename = "Email Verifier";
            $page_manager->modulelink = "email_verifier";
            $page_manager->helplink = "https://99modules.com/docs/email-verifier/";
            $page_manager->menu = ["Verified Clients" => ["href" => "", "istab" => false, "external" => false], "Unverified Clients" => ["href" => "c=UnverifiedClients", "address" => "UnverifiedClients", "istab" => false, "external" => false], "Banned IPs/Emails" => ["href" => "c=banned", "address" => "banned", "istab" => false, "external" => false], "Settings" => ["href" => "c=settings", "address" => "settings", "istab" => false, "external" => false]];
            $page_manager->startlang();
            $page_manager->header();
            if(isset($_REQUEST["saved"])) {
                echo "<div class=\"alert alert-success\">Saved Successfully!</div>";
            }
            if(isset($_REQUEST["deleted"])) {
                echo "<div class=\"alert alert-success\">Deleted Successfully!</div>";
            }
            if(isset($_REQUEST["sent"])) {
                echo "<div class=\"alert alert-success\">Sent Successfully!</div>";
            }
            if(isset($_REQUEST["unverified"])) {
                echo "<div class=\"alert alert-success\">Unverified Successfully!</div>";
            }
            if(isset($_REQUEST["verified"])) {
                echo "<div class=\"alert alert-success\">Verified Successfully!</div>";
            }
            $controller = isset($_REQUEST["c"]) ? $_REQUEST["c"] : "VerifiedClients";
            $action = isset($_REQUEST["a"]) ? $_REQUEST["a"] : "index";
            $controller .= "Controller";
            $controller = ucfirst($controller);
            if(!class_exists("\\WHMCS\\Module\\Addon\\Email_Verifier\\Adminarea\\" . $controller)) {
                redir("module=email_verifier", "addonmodules.php");
            }
            $controller = "\\WHMCS\\Module\\Addon\\Email_Verifier\\Adminarea\\" . $controller;
            $controller = new $controller();
            if(method_exists($controller, $action)) {
                $controller->{$action}($vars);
            } else {
                $controller->index($vars);
            }
            $page_manager->footer();
            break;
        case "Invalid":
            echo "License key is Invalid";
            return "";
            break;
        case "Expired":
            echo "License key is Expired";
            return "";
            break;
        case "Suspended":
            echo "License key is Suspended";
            return "";
            break;
        default:
            echo "Invalid Response";
            return "";
    }
}
function email_verifier_clientarea($vars)
{
    $licensestatus = email_verifier_license();
    switch ($licensestatus["status"]) {
        case "Active":
            $controller = isset($_REQUEST["controller"]) ? $_REQUEST["controller"] : "client";
            $action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : "index";
            $controller .= "controller";
            $controller = ucfirst($controller);
            if(!class_exists("\\WHMCS\\Module\\Addon\\Email_Verifier\\Clientarea\\" . $controller)) {
                $controller = "EmailVerifierController";
            }
            $controller = "\\WHMCS\\Module\\Addon\\Email_Verifier\\Clientarea\\" . $controller;
            $controller = new $controller();
            if(method_exists($controller, $action)) {
                return $controller->{$action}($vars);
            }
            return $controller->index($vars);
            break;
        case "Invalid":
            echo "License key is Invalid";
            return "";
            break;
        case "Expired":
            echo "License key is Expired";
            return "";
            break;
        case "Suspended":
            echo "License key is Suspended";
            return "";
            break;
        default:
            echo "Invalid Response";
            return "";
    }
}

?> 