<?php 

	echo $HTML->title_panel([
        'heading' => $Lang->get('Markdown formatting'),
    ]);

	echo $HTML->open('div.inner');
?>
   
 <p>Markdown is a simple syntax to mark-up text in your pages. It is enabled on any field that displays the Markdown link. We have listed the most common syntax here, along with some examples. There is a Markdown reference at <a href="http://daringfireball.net/projects/markdown/basics">http://daringfireball.net/projects/markdown/basics</a> however please note there are several flavours of Markdown, and some references may include extensions not currently available in Perch.</p>

<h3>Phrase modifiers:</h3>

<p>These are tags that you wrap around a word or words to change the way it looks. For example to make a word in a sentence bold you wrap it with <code>** **</code>.</p>

<div class="markup-sample">In this sentence the word **bold** will display as bold (strongly emphasised) text on the webpage.</div>

<ul>
<li><code>_emphasis_</code></li>
<li><code>**bold**</code></li>
<li><code>~~deleted text~~</code></li>
<li><code>`code`</code></li>
</ul>

<h3>Block modifiers:</h3>

<p>Using these tags will change the whole block of text that comes after the tag.   </p>

<p>Example:</p>

<div class="markup-sample"><pre><code># This is a level one heading

This is a paragraph. You do not need to use the p. tag before paragraphs unless you are forcing a change from a previous block modifier.

* List item one
* List item two
* List item three

1. Ordered lists
2. Are created
3. Like this
</code></pre></div>


<h4>More block modifiers:</h4>

<ul>
<li><code>#</code> Level 1 heading</li>
<li><code>##</code> Level 2 heading</li>
<li><code>###</code> Level 3 heading</li>
<li><code>####</code> Level 4 heading</li>
<li><code>&gt;</code> Blockquote</li>
<li><code>1.</code> Numeric list</li>
<li><code>*</code> Bulleted list</li>
</ul>

<h3>Links:</h3>

<p>To create a link, put the text of the link in square brackets and the URL in round brackets including the <code>http://</code> if it is an external link. You can link to pages on your site by giving the path from root as shown below.</p>

<div class="markup-sample"><pre><code>[Visit Google](http://google.com)
[Internal links on your site](/about/page.php)
</code></pre></div>

<h3>Punctuation:</h3>

<p>We convert dashes and quotes to correct typographical marks, and will also turn trademark and other marks into correct symbols as shown below.</p>

<ul>
<li>en -- dash → en – dash</li>
<li>em --- dash → em — dash</li>
<li>... -&gt; ellipsis </li>
</ul> 

<?php
	echo $HTML->close('div.inner');
?>