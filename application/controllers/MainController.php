<?php
namespace application\controllers;

use application\core\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $recent_posts  = $this->model->get_published_recent_posts();
        $popular_posts = $this->model->get_published_popular_posts();
        $analytics     = $this->model->get_analytics();
        $vars          = [
            'recent_posts'  => $recent_posts,
            'popular_posts' => $popular_posts,
            'analytics'     => $analytics,
        ];
        $this->view->render($vars);
    }
    public function sendfeedbackAction()
    {
        $phone   = filter_input(INPUT_POST, 'phone');
        $message = filter_input(INPUT_POST, 'message');
        if (isset($phone) && isset($message)) {
            $ip = $this->model->get_client_ip();
            $this->model->send_feedback($phone, $message, $ip);
            echo "Ваше сообщение успешно отправлено!";
        } else {
            echo "Заполните все поля!";
        }
    }
    public function articleAction()
    {
        $postid = filter_input(INPUT_GET, 'id');
        if (isset($postid)) {
            $post = $this->model->get_post($postid);
            if ($post != false) {
                $popular_posts = $this->model->get_published_popular_posts();
                $analytics     = $this->model->get_analytics();
                $vars          = [
                    'post'          => $post,
                    'popular_posts' => $popular_posts,
                    'analytics'     => $analytics,
                ];
                $ip = $this->model->get_client_ip();
                $this->model->visit_post($_GET['id'], $ip);
                $this->view->render($vars);
            } else {
                $this->view->redirect('/');
            }
        } else {
            $this->view->redirect('/');
        }
    }
    public function aboutmeAction()
    {
        $analytics = $this->model->get_analytics();
        $vars      = [
            'analytics' => $analytics,
        ];
        $this->view->render($vars);
    }
    public function closest_hospitalAction()
    {
        $analytics = $this->model->get_analytics();
        $vars      = [
            'analytics' => $analytics,
        ];
        $this->view->render();
    }
    public function agreementAction()
    {
        $analytics = $this->model->get_analytics();
        $vars      = [
            'analytics' => $analytics,
        ];
        $this->view->render();
    }
    public function complaintsAction()
    {
        $analytics = $this->model->get_analytics();
        $vars      = [
            'analytics' => $analytics,
        ];
        $this->view->render();
    }
    public function donatorsAction()
    {
        $analytics = $this->model->get_analytics();
        $vars      = [
            'analytics' => $analytics,
        ];
        $this->view->render();
    }
    public function donationAction()
    {
        $popular_posts = $this->model->get_published_popular_posts();
        $analytics     = $this->model->get_analytics();
        $vars          = [
            'popular_posts' => $popular_posts,
            'analytics'     => $analytics,
        ];
        $this->view->render($vars);
    }
    public function treatment_planAction()
    {
        $symptoms  = $this->model->get_symptoms();
        $analytics = $this->model->get_analytics();
        $vars      = [
            'symptoms'  => $symptoms,
            'analytics' => $analytics,
        ];
        $this->view->render($vars);
    }
    public function verificationAction()
    {
        $message = $this->model->unsent_sms();
        $key     = filter_input(INPUT_GET, 'key');
        $type    = filter_input(INPUT_GET, 'type');
        if ($key === '0773748984') {
            if ($type === 'message') {
                echo (json_encode($message, JSON_UNESCAPED_UNICODE));
            } elseif ($type === 'send') {
                $id = filter_input(INPUT_GET, 'id');
                $this->model->send_sms($id);
            } else {
                //echo '0';
                echo (json_encode($message, JSON_UNESCAPED_UNICODE));
            }
        } else {
            echo "0";
        }
    }
}
