<?php
namespace Xiaosongshu;
require __DIR__ .'/../vendor/autoload.php';

use GuzzleHttp\Client as HtClient;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Promise;
class Mabi
{

    /**
     * 并发请求
     * @param string $base =$base_uri
     * @param array $param =[
     *   'request_name1'=>[
     *      'url'=>$url1,
     *      'param'=>[
     *          'key1'=>value1,
     *          'key2'=>value2,
     *          ...
     * ]
     * ],
     * 'request_name2'=>[
     *      'url'=>$url2,
     *      'param'=>[
     *          'key1'=>value1,
     *          'key2'=>value2,
     *          ...
     * ]
     * ],
     * .....
     * ]
     * @return array=>[
     *      'request_name1'=>[],
     *      'request_name2'=>[],
     *      ...
     * ]
     * @throws \Throwable
     * @author yanglong
     * @datetime 2022-6-20 16:43
     */
    public function ManyRequest(string $base, array $param): array
    {
        $client   = new HtClient(['base_uri' => $base]);
        $promises = [];
        foreach ($param as $k => $v) {
            $promises[$k] = $client->getAsync($v['url'], [RequestOptions::QUERY => $v['param']]);
        }
        $results = Promise\unwrap($promises);
        $back    = [];
        foreach ($param as $k => $v) {
            if ($results[$k]->getStatusCode() == 200) {
                $back[$k] = json_decode($results[$k]->getBody()->getContents(), true);
            } else {
                $back[$k] = [];
            }
        }
        return $back;
    }
}