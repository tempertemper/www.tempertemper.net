<?php 

	echo $HTML->title_panel([
        'heading' => $Lang->get('Textile formatting'),
    ]);

	echo $HTML->open('div.inner');
?>

<p>Textile is a simple syntax to mark-up text in your pages. It is enabled on any field that displays the Textile link. We have listed the most common syntax here, along with some examples. There is a full Textile reference at <a href="http://txstyle.org/">http://txstyle.org/</a> </p>

<h3>Phrase modifiers:</h3>

<p>These are tags that you wrap around a word or words to change the way it looks. For example to make a word in a sentence bold you wrap it with <code>**</code>.</p>

<div class="markup-sample">In this the word <code>*bold*</code> will display as bold text on the webpage.</div>

<ul>
<li><code>_emphasis_</code></li>
<li><code>*bold*</code></li>
<li><code>??citation??</code></li>
<li><code>-deleted text-</code></li>
<li><code>+inserted text+</code></li>
<li><code>^superscript^</code></li>
<li><code>~subscript~</code></li>
<li><code>%span%</code></li>
<li><code>@code@</code></li>
</ul>

<h3>Block modifiers:</h3>

<p>Using these tags will change the whole block of text that comes after the tag. Two important things to remember when using block modifiers are that after the period, you need to leave a space and you need to leave a full linespace between the end of the block and the start of the next one. </p>

<h4>Example:</h4>

<div class="markup-sample"><pre><code>h1. This is a level one heading

This is a paragraph. You do not need to use the p. tag before paragraphs unless you are forcing a change from a previous block modifier.

* List item one
* List item two
* List item three
</code></pre></div>

<h4>More block modifiers:</h4>

<ul>
<li><strong>h1.</strong> Level 1 heading</li>
<li><strong>h2.</strong> Level 2 heading</li>
<li><strong>h3.</strong> Level 3 heading</li>
<li><strong>h4.</strong> Level 4 heading</li>
<li><strong>bq.</strong> Blockquote</li>
<li><strong>p.</strong> Paragraph</li>
<li><strong>bc.</strong> Block code</li>
<li><strong>pre.</strong> Pre-formatted</li>
<li><strong>#</strong> Numeric list</li>
<li><strong>*</strong> Bulleted list</li>
</ul>

<h3>Links:</h3>

<p>To create a link, put the text of the link in quotes, then a <code>:</code> and then the full URL including the <code>http://</code> if it is an external link. You can link to pages on your site by giving the path from root as shown below.</p>

<div class="markup-sample"><pre><code>&quot;Visit Google&quot;:http://google.com
&quot;Internal links on your site&quot;:/about/page.php
</code></pre></div>

<h3>Punctuation:</h3>

<p>Textile converts dashes and quotes to correct typographical marks, and will also turn trademark and other marks into correct symbols as shown below.</p>

<ul>
<li>em -- dash → em — dash</li>
<li>en - dash → en – dash</li>
<li>foo(tm) → foo™</li>
<li>foo(r) → foo®</li>
<li>foo(c) → foo© </li>
</ul>


<?php
	echo $HTML->close('div.inner');
?>