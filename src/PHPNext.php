<?php

namespace gaucho;

class PHPNext
{
    public $httpResponseCode;
    public $localFileName;
    public $mimeType;
    public $outDir;
    public $requestURI;
    public $routeType;
    public function __construct($outDir)
    {
        $this->outDir=$outDir;
        $this->requestURI=$_SERVER['REQUEST_URI'];
        $this->setLocalFileName();
    }
    public function segment($segmentId = null)
    {
        $str=$_SERVER["REQUEST_URI"];
        $str=@explode('?', $str)[0];
        $arr=explode('/', $str);
        $arr=array_filter($arr);
        $arr=array_values($arr);
        if (count($arr)<1) {
            $segment[1]='index';
        } else {
            $i=1;
            foreach ($arr as $key => $value) {
                $segment[$i++]=$value;
            }
        }
        if (is_null($segmentId)) {
            return $segment;
        } else {
            if (isset($segment[$segmentId])) {
                return $segment[$segmentId];
            } else {
                return false;
            }
        }
    }
    public function setLocalFileName($localFileNameStr=null)
    {
        $routeType=null;
        $httpResponseCode=200;
        if (is_null($localFileNameStr)) {
            $pathStr=$this->outDir.'/'.$this->segment(1);
            $staticFile=explode('?', $_SERVER['REQUEST_URI'])[0];
            $staticFile=urldecode($staticFile);
            $staticFile=$this->outDir.$staticFile;
            $staticPage=$pathStr.'.html';
            $dynamicRoot='';
            $arr=glob($this->outDir.'/\[*\].html');
            if (isset($arr[0])) {
                $dynamicRoot=$arr[0];
            }
            $dynamicDir='';
            $arr=glob($pathStr.'/\[*\].html');
            if (isset($arr[0])) {
                $dynamicDir=$arr[0];
            }
            $staticDir=$pathStr.'/'.$this->segment(2).'.html';
            if (
                file_exists($staticFile) and
                !is_dir($staticFile)
            ) {//arquivo estático
                $localFileNameStr=$staticFile;
                $routeType='static file';
            } elseif (file_exists($staticPage)) {//página estática na raiz
                $localFileNameStr=$staticPage;
                $routeType='static page';
            } elseif (file_exists($staticDir)) {//página estática em um diretório
                $localFileNameStr=$staticDir;
                $routeType='static dir';
            } elseif (file_exists($dynamicRoot)) {//página dinâmica na raíz
                $localFileNameStr=$dynamicRoot;
                $routeType='dynamic root';
            } elseif (file_exists($dynamicDir)) {//página dinâmica em um diretório
                $localFileNameStr=$dynamicDir;
                $routeType='dynamic dir';
            } else {//not found
                $localFileNameStr=$this->outDir.'/404.html';
                $routeType='not found';
                $httpResponseCode=404;
            }
        }
        $this->httpResponseCode=$httpResponseCode;
        $this->localFileName=$localFileNameStr;
        $this->routeType=$routeType;
        $this->setMimeType($this->localFileName);
    }
    public function setMimeType($localFileName)
    {
        $arr=explode('.', $localFileName);
        end($arr);
        $idx=$arr[key($arr)];
        $mimet = array(
            // diversos
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            'webp'=>'image/webp',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',
            'bin' => 'application/octet-stream',

            // audio/video
            'mp4'=>'video/mp4',
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'docx' => 'application/msword',
            'xlsx' => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
        if (isset($mimet[$idx])) {
            $this->mimeType=$mimet[$idx];
        } else {
            $this->mimeType='text/html';
        }
    }
    public function print($print=true)
    {
        if ($print) {
            header('Content-Type: '.$this->mimeType);
            http_response_code($this->httpResponseCode);
            die(file_get_contents($this->localFileName));
        } else {
            return [
                'httpResponseCode'=>$this->httpResponseCode,
                'requestUri'=>$this->requestURI,
                'localFileName'=>$this->localFileName,
                'mimeType'=>$this->mimeType,
                'routeType'=>$this->routeType
            ];
        }
    }
}
