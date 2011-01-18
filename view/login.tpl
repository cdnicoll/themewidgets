<h3>Working Environment: {$environment}</h3>

<div id="login" style="width:60%; float:left">
<form>
	Username: <input type="text" name="text_username" />
	<br/>
	Password: <input type="text" name="text_username" />
	<br/>
	<input type="submit" name="submit" value="login" />
</form>
</div>

<div id=rssFeed style="width:30%; float:right">
	{foreach from=$rss key=k item=v}
		<a href="{$v.link}" style="font-size:105%; font-weight:bold; padding:0px; margin:0px; font-style:italic" target="_blank">{$v.title}</a>
 		<h3 style="font-size:80%; padding-bottom:4px; margin:0px;">{$v.pubDate}</h3>
 		{$v.description}<br/>
 		<a href="{$v.link} target="_blank">Read More...</a>
 	{/foreach}
</div>