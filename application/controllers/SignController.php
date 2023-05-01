<?php
namespace application\controllers;

use application\core\Controller;

class SignController extends Controller
{
    protected $db;
    private function connectDb()
    {
        $config   = require 'application/config/db.php';
        $this->db = mysqli_connect($config['host'], $config['user'], $config['password'], $config['dbname']);
        if (mysqli_connect_errno()) {
            exit('Failed to connect to MySQL: ' . mysqli_connect_error());
        }
    }
    public function sign_inAction()
    {
        $analytics = $this->model->get_analytics();
        $vars      = [
            'analytics' => $analytics,
        ];
        $this->view->render($vars);
    }
    public function sign_upAction()
    {
        $analytics = $this->model->get_analytics();
        $vars      = [
            'analytics' => $analytics,
        ];
        $this->view->render($vars);
    }
    public function signupAction()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $name = filter_input(INPUT_POST, 'name');
            if (isset($name)) {
                $address      = filter_input(INPUT_POST, 'address');
                $city         = filter_input(INPUT_POST, 'city');
                $oblast       = filter_input(INPUT_POST, 'oblast');
                $position     = filter_input(INPUT_POST, 'position');
                $passport     = filter_input(INPUT_POST, 'passport');
                $phone        = filter_input(INPUT_POST, 'phone');
                $passwd       = filter_input(INPUT_POST, 'passwd');
                $tunits       = filter_input(INPUT_POST, 'tunits');
                $phone_exists = $this->model->user_exists($passport, $phone);
                if ($phone_exists != 1) {
                    $ip           = $this->model->get_client_ip();
                    $add_hospital = $this->model->add_hospital($name, $address, $city, $oblast, $position, $tunits, $passport, $phone, $passwd, $ip);
                    echo "created";
                } else {
                    echo "exists";
                }
            } else {
                echo "empty";
            }
        }
    }
    public function authenticateAction()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            if (isset($_POST['username'])) {
                $username = filter_input(INPUT_POST, 'username');
                $this->connectDb();
                if (is_numeric($username)) {
                    $stmt    = $this->db->prepare('SELECT id, valid, isHead, phone, password FROM doctor_users WHERE phone = ?');
                    $isAdmin = 0;
                } else {
                    $stmt    = $this->db->prepare('SELECT id, valid, username, password FROM admin WHERE username = ?');
                    $isAdmin = 1;
                }
                if ($stmt) {
                    $stmt->bind_param('s', $username);
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows > 0) {
                        if ($isAdmin === 1) {
                            $stmt->bind_result($id, $valid, $username, $password);
                        } else {
                            $stmt->bind_result($id, $valid, $isHead, $username, $password);
                        }
                        $stmt->fetch();
                        if ($isAdmin === 1) {
                            $service = 1;
                        } else {
                            if ($isHead === 1) {
                                $service = 103;
                            } else {
                                $service = 100;
                            }
                        }
                        if (password_verify($_POST['password'], $password)) {
                            session_regenerate_id();
                            $key                 = $this->model->get_random_passwd();
                            $_SESSION['authkey'] = $key;
                            $_SESSION['user']    = $id;
                            $_SESSION['service'] = $service;
                            $ip                  = $this->model->get_client_ip();
                            if ($this->model->isExist($id, $service)) {
                                $this->model->updateKey($id, $key, $service, $ip);
                                if ($isAdmin === 1) {
                                    if ($valid === 1) {
                                        echo "/admin";
                                    } else {
                                        session_destroy();
                                        echo "valid";
                                    }
                                } else {
                                    if ($isHead === 1) {
                                        if ($valid === 1) {
                                            echo "/hospital";
                                        } else {
                                            session_destroy();
                                            echo "valid";
                                        }
                                    } else {
                                        if ($valid === 1) {
                                            echo "/doctor";
                                        } else {
                                            session_destroy();
                                            echo "valid";
                                        }
                                    }
                                }
                            } else {
                                $this->model->setKey($id, $key, $service, $ip);
                                if ($isAdmin === 1) {
                                    if ($valid === 1) {
                                        echo "/admin";
                                    } else {
                                        session_destroy();
                                        echo "valid";
                                    }
                                } else {
                                    if ($isHead === 1) {
                                        if ($valid === 1) {
                                            echo "/hospital";
                                        } else {
                                            session_destroy();
                                            echo "valid";
                                        }
                                    } else {
                                        if ($valid === 1) {
                                            echo "/doctor";
                                        } else {
                                            session_destroy();
                                            echo "valid";
                                        }
                                    }
                                }
                            }
                        } else {
                            echo "password";
                        }
                    } else {
                        echo "username";
                    }
                    $stmt->close();
                }
            } else {
                echo "empty";
            }
        }
    }

}
