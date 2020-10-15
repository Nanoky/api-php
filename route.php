<?php

    include_once "DB.php";

    //allow access
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

    $config = [
        'sgbd' => 'mysql',
        'host' => 'localhost',
        'dbname' => 'eq_tester',
        'username' => 'root',
        'password' => ''
    ];

    $uri = $_SERVER['REQUEST_URI'];
    $uri = explode('/', rtrim($uri, "/"));

    $method = $_SERVER['REQUEST_METHOD'];

    try {
        $db = new DB($config);
    } catch (Exception $e) {
        respond(false, [], $e->getMessage());
    }

    if ($method == 'GET')
    {
        //get with email
        if (!empty($uri[1]))
        {
            $email = $uri[1];
            $data = [];
            try {
                $data = $db->select("SELECT * FROM tester WHERE email=:email", [
                    'email' => $email
                ]);

                respond(true, $data);
            } catch (Exception $e) {
                respond(false, [], $e->getMessage());
            }
                        
            respond(false, [], "No data found");
        }

        //get all
        $data = [];
        try {
            $data = $db->select("SELECT * FROM tester");
            respond(true, $data);
        } catch (Exception $e) {
            respond(false, [], $e->getMessage());
        }

        respond(false, [], "no data found");
    }

    if ($method == 'POST')
    {
        if (!empty($_POST))
        {
            $data = false;

            try {
                $data = $db->set("INSERT INTO tester (email, name, firstname, organization) VALUES(:email, :name, :firstname, :organization)", $_POST);
            } catch (Exception $e) {
                respond(false, [], $e->getMessage());
            }

            if ($data)
            {
                respond(true);
            }

            respond(false, [], "Something wrong happened");
        }

        respond(false, [], "no data request found");
    }

    if ($method == 'PUT')
    {
        if (!empty($uri[1]))
        {
            $r_data = [];
            parse_str(file_get_contents("php://input"), $r_data);
            $r_data['id'] = $uri[1];

            if (!empty($r_data))
            {
                $data = false;

                try {
                    $data = $db->set("UPDATE tester SET name=:name, firstname=:firstname, organization=:organization WHERE email=:email", $r_data);
                } catch (Exception $e) {
                    respond(false, [], $e->getMessage());
                }

                if ($data)
                {
                    respond(true);
                }

                respond(false, [], "Something wrong");
            }
        }

        respond(false, [], "Request data not found");
    }

    if ($method == 'DELETE')
    {
        if (!empty($uri[1]))
        {
            $r_data = [];
            $r_data['id'] = $uri[1];

            if (!empty($r_data))
            {
                $data = false;

                try {
                    $data = $db->set("DELETE FROM tester WHERE email=:email", $r_data);
                } catch (Exception $e) {
                    respond(false, [], $e->getMessage());
                }

                if ($data)
                {
                    respond(true);
                }

                respond(false, [], "Something wrong");
            }
        }

        respond(false, [], "Request data not found");
    }

    function respond($success = false, $data = [], $message = "")
    {
        echo json_encode([
            'success' => $success,
            'data' => $data,
            'message' => $message
        ]);
        die;
    }

?>