<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise;
use GuzzleHttp\Pool;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ClientException;




$client = new GuzzleHttp\Client(['base_uri' => 'http://httpbin.org']);

$onRediect = function (
    RequestInterface $request,
    ResponseInterface $response,
    UriInterface $uri
) {
    echo 'Redirecting '.$request->getUri().'to '.$uri."\n";
};

$res = $client->request('GET', '/redirect/3', [
    'allow_redirects' => [
        'max' => 10,
        'strict' => true,
        'referer' => true,
        'protocls' => ['https'],
        'on_redirect' => $onRediect,
        'track_redirects' => true
    ]
]);

echo $res->getStatusCode();

var_dump($res->getHeader('X-Guzzle-Redirect-History'));
exit;






$res = $client->request('GET', '/redirect/3', [
    'allow_redirects' => false
]);

var_dump($res->getHeader('X-Guzzle-Redirect-History'));
exit;



$client = new Client();
try {
    $client->request('GET', 'https://github.com/_abc_123_404');
} catch (ClientException $e) {
    print_r($e->getRequest());
    var_dump($e->getResponse());
}
exit;



$response = $client->request('GET', 'http://www.xiaonei.com', [
    'allow_redirects' => false
]);
echo $response->getStatusCode();
exit;


$client = new Client();
$requests = function ($total) use ($client) {
    $uri = 'http://httpbin.org/';
    for ($i = 0; $i < $total; $i++) {
        yield function () use ($client, $uri) {
            return $client->getAsync($uri);
        };
    }
};

$pool = new Pool($client, $requests(100));
$promise = $pool->promise();
$promise->wait();
exit;







$requests = function ($total) {
    $uri = 'http://httpbin.org/';
    for ($i = 0; $i < $total; $i++) {
        yield new Request('GET', $uri);
    }
};

$pool = new Pool($client, $requests(100), [
    'concurrency' => 5,
    'fullfilled' => function ($response, $index) {

    },
    'rejected' => function ($reason, $index) {

    },
]);
$promise = $pool->promise();
$promise->wait();
exit;



$client = new Client(['base_uri' => 'http://httpbin.org/']);
$promises = [
    'image' => $client->getAsync('/image'),
    'png' => $client->getAsync('/image/png'),
    'jpeg' => $client->getAsync('/image/jpeg'),
    'webp' => $client->getAsync('/image/webp')
];

$results = Promise\unwrap($promises);

var_dump($results['image']->getHeader('Content-Length'));
exit;





$client = new Client([
    'base_uri' => 'http://httpbin.org',
    'timeout' => 2.0,
]);

$headers = ['X-Foo' => 'Bar'];
$body = 'Hello!';
$request = new Request('HEAD', 'http://httpbin/org', $headers, $body);
//$response = $client->sendAsync($request);
$promise = $client->requestAsync('GET', 'http://httpbin.org/get');
$promise->then(
    function (ResponseInterface $res) {
        echo $res->getStatusCode();
    },
    function (RequestException $e) {
        echo $e->getMessage()."\n";
        echo $e->getRequest()->getMethod();
    }
);

$promise = $client->getAsync('http://httpbin.org/get');
$promise = $client->deleteAsync('http://httpbin.org/delete');
$promise = $client->headAsync('http://httpbin.org/get');
$promise = $client->optionsAsync('http://httpbin.org/get');
$promise = $client->patchAsync('http://httpbin.org/patch');
$promise = $client->postAsync('http://httpbin.org/post');
$promise = $client->putAsync('http://httpbin.org/put');


$request = new Request('PUT', 'http://httpbin.org/put');
$response = $client->send($request, ['timeout' => 2]);

$response = $client->get('http://httpbin.org/get');
$response = $client->delete('http://httpbin.org/delete');
$response = $client->head('http://httpbin.org/get');
$response = $client->options('http://httpbin.org/get');
$response = $client->patch('http://httpbin.org/patch');
$response = $client->post('http://httpbin.org/post');
$response = $client->put('http://httpbin.org/put');

$client = new GuzzleHttp\Client(['base_uri' => 'https://foo.com/api/']);
$response = $client->request('GET', 'test');
$response = $client->request('GET', '/root');