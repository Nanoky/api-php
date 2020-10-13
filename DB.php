<?php

    class DB
    {

        public function __construct($config)
        {
            try {
                $this->db = new PDO($config['sgbd'] . ":host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['username'], $config['password']);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $th) {
                throw $th;
            }
        }

        public function select($query, $data = [])
        {
            try {
                $statement = $this->db->prepare($query);

                foreach ($data as $key => $value) {
                    $statement->bindParam(":" . $key, $data[$key]);
                }

                $statement->execute();

                return $statement->fetchAll();

            } catch (Exception $th) {
                throw $th;
            }
        }

        public function set($query, $data)
        {
            try {
                $statement = $this->db->prepare($query);

                foreach ($data as $key => $value) {
                    $statement->bindParam(":" . $key, $data[$key]);
                }

                $statement->execute();

                return true;

            } catch (Exception $th) {
                throw $th;
            }
            
            return false;
        }

        
    }

?>