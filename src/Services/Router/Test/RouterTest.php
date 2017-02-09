<?php

namespace App\Services\Router\Test;
use App\Services\Router\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    private $isGetExe   = false;
    private $isAnyExe   = false;
    private $params     = [];
    private $userParams = [];

    public function testGetMethod()
    {
        $router = new Router('/home/post', 'get');
        $router->get('/home/post', function(){
            $this->isGetExe = true;
        });
        $router->dispatch();
        $this->assertTrue($this->isGetExe);
    }

    public function testAnyMethod()
    {
        $router = new Router('/home/post', 'get');
        $router->any('/home/post', function(){
            $this->isAnyExe = true;
        });
        $router->dispatch();
        $this->assertTrue($this->isAnyExe);
    }


    public function testRouterUseParamsAndRex()
    {
        $router = new Router('/post/50/comment/45', 'get');
        $router->get('/post/{postId}/comment/{commentId}', function($postId, $commentId){
            $this->params = [$postId, $commentId];
        });
        $router->dispatch();
        $this->assertEquals([50, 45], $this->params);
    }

    public function testRouterWithUserRex()
    {
        $router = new Router('/post/aabb/comment/80', 'get');
        $router->get('/post/{postId}/comment/{commentId}', function($postId, $commentId){
            $this->userParams = [$postId, $commentId];
        })->where(['postId' => '[a-z]+']);

        $router->dispatch();
        $this->assertEquals(['aabb', 80], $this->userParams);
    }

    public function testPostMethod()
    {
    }

    public function testHeadMethod()
    {
    }


    /**
     * undocumented function
     *
     * @return void
     */
    //public function testSth()
    //{
        //$tokens = [];
        //if(preg_match('/\{[A-Za-z0-9]+\}/', '{name}/{sex}/{height}', $tokens, \PREG_OFFSET_CAPTURE) === false) {
            //throw new Exception('error happen');
        //}
        //var_dump($tokens);
    //}


}
