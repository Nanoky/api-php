<?php

    include_once "DB.php";

    //allow access
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

    $config = [
        'sgbd' => 'mysql',
        'host' => 'localhost',
        'dbname' => 'id15306582_eq_users',
        'username' => 'id15306582_admin',
        'password' => 'tcHLEB(jJ&+5+Ex_'
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
            if ($uri[1] == 'single')
            {
                if (!empty($uri[2]))
                {
                    $email = $uri[2];
                    $data = [];
                    try {
                        $data = $db->select("SELECT users.email as email, users.password as password, users.username as username, users.name as name, users.firstname as firstname, users.organization as organization, users.active as active, users.created_at as created_at, users.updated_at as updated_at, roles.id as id_role, roles.title as role
                            FROM users 
                            INNER JOIN roles ON users.id_role=roles.id 
                            WHERE email=:email", [
                            'email' => $email
                        ]);

                        respond(true, $data);
                    } catch (Exception $e) {
                        respond(false, [], $e->getMessage());
                    }
                                
                    respond(false, [], "No data found");
                }
            }

            if ($uri[1] == 'testers')
            {
                $email = $uri[1];
                $_tester_role = 2;
                $data = [];
                try {
                    $data = $db->select("SELECT users.email as email, users.password as password, users.username as username, users.name as name, users.firstname as firstname, users.organization as organization, users.active as active, users.created_at as created_at, users.updated_at as updated_at, roles.id as id_role, roles.title as role
                        FROM users 
                        INNER JOIN roles ON users.id_role=roles.id 
                        WHERE id_role=:id_role", [
                        'id_role' => $_tester_role
                    ]);

                    respond(true, $data);
                } catch (Exception $e) {
                    respond(false, [], $e->getMessage());
                }
                            
                respond(false, [], "No data found");
            }

            respond(false, [], "404 url not found");
            
        }

        //get all
        $data = [];
        try {
            $data = $db->select("SELECT users.email as email, users.password as password, users.username as username, users.name as name, users.firstname as firstname, users.organization as organization, users.active as active, users.created_at as created_at, users.updated_at as updated_at, roles.id as id_role, roles.title as role
            FROM users 
            LEFT JOIN roles ON users.id_role=roles.id");
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
                $data = $db->set("INSERT INTO users (email, password, username, name, firstname, organization, active, created_at, updated_at, id_role) VALUES(:email, :password, :username, :name, :firstname, :organization, :active, :created_at, :updated_at, :id_role)", $_POST);
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
            
            if (!empty($uri[2]))
            {
                if ($uri[1] = "active")
                {
                    $r_data['email'] = $uri[2];

                    if (!empty($r_data))
                    {
                        $data = false;

                        try {
                            $data = $db->set("UPDATE users SET active=:active WHERE email=:email", $r_data);
                        } catch (Exception $e) {
                            respond(false, $r_data, $e->getMessage());
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

            $r_data['email'] = $uri[1];

            if (!empty($r_data))
            {
                $data = false;

                try {
                    $data = $db->set("UPDATE users SET name=:name, username=:username, password=:password, firstname=:firstname, organization=:organization, updated_at=:updated_at WHERE email=:email", $r_data);
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
            $r_data['email'] = $uri[1];

            if (!empty($r_data))
            {
                $data = false;

                try {
                    $data = $db->set("DELETE FROM users WHERE email=:email", $r_data);
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