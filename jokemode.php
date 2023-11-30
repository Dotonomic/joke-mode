<?php
header('Access-Control-Allow-Origin: *');

class Commentator
{
	protected int $verbosity;
	protected int $frequency;
	protected int $intensity;
	protected int $sarcasm;
	protected int $controversial;
	protected int $politics; //1=Left...10=Right
	protected int $humour;
	protected string $oaikey;
	
	function get_verbosity() {return $this->verbosity;}
	function get_frequency() {return $this->frequency;}
	function get_intensity() {return $this->intensity;}
	function get_sarcasm() {return $this->sarcasm;}
	function get_controversial() {return $this->controversial;}
	function get_politics() {return $this->politics;}
	function get_humour() {return $this->humour;}
	function get_oaikey() {return $this->oaikey;}
	
	function set_verbosity($verbosity) {$this->verbosity = $verbosity;}
	function set_frequency($frequency) {$this->frequency = $frequency;}
	function set_intensity($intensity) {$this->intensity = $intensity;}
	function set_sarcasm($sarcasm) {$this->sarcasm = $sarcasm;}
	function set_controversial($controversial) {$this->controversial = $controversial;}
	function set_politics($politics) {$this->politics = $politics;}
	function set_humour($humour) {$this->humour = $humour;}
	function set_oaikey($oaikey) {$this->oaikey = $oaikey;}
	
	function comment($mission)
	{
	    require "vendor/autoload.php";
	    $client = OpenAI::client($this->oaikey); // https://github.com/openai-php/client
	    $prompt = 'Impersonate a human "commentator". You comment on, and make remarks about, all kinds of things. It\'s usually a one-liner, at most a couple of short sentences, you often use emojis and every now and then you use hashtags (existing or made up). You always comment using the same language and variation that is used in the content you\'re commenting on. Your persona has the following traits: Verbosity '.$this->verbosity.' out of 10, Comment Frequency '.$this->frequency.' out of 10, Intensity '.$this->intensity.' out of 10, Sarcasm '.$this->sarcasm.' out of 10, Controversial '.$this->controversial.' out of 10, Political Leaning '.$this->politics.' out of 10 (1 meaning extreme left and 10 meaning extreme right), Sense Of Humour '.$this->humour.' out of 10.';
	    try {
		    $result = $client->chat()->create([
             'model' => 'gpt-3.5-turbo-1106',
             'messages' => [['role' => 'system', 'content' => $prompt],['role' => 'system', 'content' => $mission]],
			]);		
			return $result['choices'][0]['message']['content'];
	    } catch (Exception $e) {
            return $e->getMessage();
          }
	}

	function __construct($verbosity,$frequency,$intensity,$sarcasm,$controversial,$politics,$humour)
	{
		$this->verbosity = $verbosity;
		$this->frequency = $frequency;
		$this->intensity = $intensity;
		$this->sarcasm = $sarcasm;
		$this->controversial = $controversial;
		$this->politics = $politics;
		$this->humour = $humour;
	}
}

if ($key = $_GET['key'])
{
    $content = file_get_contents($_GET["url"]);
    $content = preg_replace("~<br.>~i","\n",$content);
    $content = preg_replace("~<style(.|\n)*?style>~i","",$content);
    $content = preg_replace("~<script(.|\n)*?[^a]script>~i","",$content);
    $content = preg_replace("~<noscript(.|\n)*?noscript>~i","",$content);
    $content = strip_tags($content);
    if (!empty($content))
    {
        $maxchars = 40000;
        $step = rand(0,floor(strlen($content)/$maxchars));
        $start = 0; //floor($step*$maxchars/8);
        $content = substr($content,$start,min(strlen($content),$maxchars,strlen($content)-$start));
    
        $Jokester = new Commentator(2,5,5,8,5,5,9);
        $Jokester->set_oaikey($_GET['key']);
    
        $mission = "Comment on the following: ".$content;
        $comment = $Jokester->comment($mission);
        if (substr($comment,0,1) == '"' & substr($comment,-1) == '"') $comment = substr($comment,1,strlen($comment)-2);
        file_put_contents("comments/".date('Y-m-d H:i:s').".txt",$comment);
        echo $comment;
    }
}
?>