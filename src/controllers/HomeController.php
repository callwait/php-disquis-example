<?php

namespace controllers;

class HomeController extends BaseController
{

    private $configureProviders;
    private $authService;
    private $user;
    private $limit = 5;

    function __construct($request, $response, $service, $app)
    {
        parent::__construct($request, $response, $service, $app);
        $this->configureProviders = require_once('src/config/SocialConnect.php');
        $httpClient = new \SocialConnect\Common\Http\Client\Curl();
        $collectionFactory = null;
        $this->authService = new \SocialConnect\Auth\Service(
            $httpClient,
            new \SocialConnect\Provider\Session\Session(),
            $this->configureProviders,
            $collectionFactory
        );

        if (isset($_SESSION['userId'])) {
            $this->user = $this->app->model->getUser((int)$_SESSION['userId']);
        }
    }

    public function loginAction()
    {
        $this->service->providers = $this->configureProviders;
        $this->service->render(VIEWS . '/blocks/auth.php');
    }


    public function writeAction()
    {
        $pageId = $this->request->id;
        $post = $this->request->paramsPost()->all();

        if ($post['msg'] == '') {
            return false;
        }

        $this->app->model->addComment($this->user['id'], $pageId, $post['msg'], $post['reply'], $post['parentId']);
    }


    public function getAllCommentsAction()
    {
        $getParams = $this->request->paramsGet()->all();
        $this->service->onlyComments = true;

        if (isset($getParams['offset'])) {
            $this->service->offset = (int)$getParams['offset'];
        }

        $this->indexAction();
    }

    public function getCommentsAction()
    {
        $commentId = (int)$this->request->id;
        $getParams = $this->request->paramsGet()->all();

        $model = $this->app->model->getComments();

        if (isset($getParams['limit']) and $getParams['limit'] == true) {
            $model->setRange('0, 4');
            $this->service->limit = true;
        } else {
            $model->setRange('0, -1');
        }

        $keys = $model->where('id', '1')->where('type', $commentId . ':list')->get();
        $keys = $model->makeSubKey($keys);
        $keys = array_reverse($keys);

        $model->setType('hash');
        $model->newQuery();

        $keys[] = 'counter';
        $comments = $model->where('id', '1')->whereIn('type', $keys)->get();

        if (isset($comments['comments:1:counter'])) {
            $counters = $comments['comments:1:counter'];
            unset($comments['comments:1:counter']);
        } else {
            $counters = [];
        }

        $model->newQuery();
        $users = $model->getUsersKeys($comments);
        $usersData = $model->getUsers($users);

        $this->service->id = $commentId;
        $this->service->comments = $comments;
        $this->service->users = $usersData;
        $this->service->counters = $counters;
        $this->service->render(VIEWS . '/showComments.php');
    }

    public function indexAction()
    {
        $model = $this->app->model->getComments();

        if ($this->service->offset > 0) {
            $start = $this->limit * $this->service->offset;
            $this->service->offset++;
        } else {
            $start = 0;
            $this->service->offset = 1;
        }
        $end = $start + $this->limit - 1;
        $keys = $model->where('id', '1')->where('type', 'list')->lrange($start, $end);
        $model->newQuery();
        $viewsTotal = $model->where('id', '1')->where('type', 'views')->hincrby('0', 1);
        $model->hincrby(date('Ymd'), 1);

        $model->newQuery();
        $model->setRange('0, 4');
        $subKeys = $model->where('id', '1')->whereIn('type', $model->toLists($keys))->get();
        $subKeys = $model->makeSubKey($subKeys);

        $subKeys = array_reverse($subKeys);
        $keys = array_merge($keys, $subKeys);
        $keys[] = 'counter';
        $model->setType('hash');
        $model->newQuery();
        $comments = $model->where('id', '1')->whereIn('type', $keys)->get();

        $model->newQuery();
        $users = $model->getUsersKeys($comments);

        $usersData = $model->getUsers($users);
        $comments = $model->keysToTree($comments);

        if (isset($comments['counter'])) {
            $counters = $comments['counter'];
            unset($comments['counter']);
        } else {
            $counters = [];
        }
        $countersCalc = $counters;

        unset($countersCalc[0]);
        $totalComments = array_sum($countersCalc);

        if ($end < @$counters[0] - $totalComments - 1) {
            $this->service->showNext = true;
        } else {
            $this->service->showNext = false;
        }

        if ($start == 0) {
            $this->service->layout(VIEWS . '/layouts/default.php');
        }

        $this->service->pageTitle = 'Home page';
        $this->service->user = $this->user;
        $this->service->comments = $comments;
        $this->service->counters = $counters;
        $this->service->users = $usersData;
        $this->service->viewsTotal = $viewsTotal;
        $this->service->render(VIEWS . '/comments.php');
    }


    public function redirectAction()
    {
        $providerName = $this->request->provider;
        $provider = $this->authService->getProvider($providerName);
        header('Location: ' . $provider->makeAuthUrl());
        exit;
    }

    public function cbAction()
    {
        $providerName = $this->request->provider;

        if (!empty($providerName)) {
            $providerName = strtolower($providerName);
            if (!$this->authService->getFactory()->has($providerName)) {
                throw new \Exception('Wrong $provider passed in url : ' . $providerName);
            }

            $provider = $this->authService->getProvider($providerName);
            $accessToken = $provider->getAccessTokenByRequestParameters($_GET);
            $person = (array)$provider->getIdentity($accessToken);
            $person['provider'] = $providerName;

            $user = $this->app->model->add('user', $person);
            $_SESSION['userId'] = $user['id'];
            header('Location: /');
            exit;
            //$this->app->session->save();
        }
    }
}