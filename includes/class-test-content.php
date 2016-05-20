<?php
namespace testContent;

/**
 * Test Content
 *
 * Class to create test content on the fly for multiple situations.
 *
 * Run any of the methods in this class to get random data of a particular type.
 * All methods are static and can be called indepenently of the type of data
 * you're trying to create (i.e. posts, cpts, or even cutom table data).
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class TestContent{

	/**
	 * Title function.
	 *
	 * Builds a short random title.
	 *
	 * @see substr
	 *
	 * @param int $num_words Number of words to return.
	 * @return string Random title string.
	 */
	public static function title( $num_words = '' ){

		$title = '';

		$random_words = array(
			'lorem',
			'ipsum',
			'dolor',
			'sit',
			'amet',
			'consectetur',
			'adipiscing',
			'elit',
			'sed',
			'porttitor',
			'augue',
			'vitae',
			'ante',
			'posuere',
			'aecenas',
			'ultricies',
			'neque',
			'ut',
			'enim',
			'pharetra',
			'sodales',
			'pellentesque',
			'gravida',
			'mauris',
			'pellentesque',
			'cum',
			'sociis',
			'natoque',
			'penatibus',
			'et',
			'magnis',
			'dis'

		);

		// If we didn't choose a count, make one
		if ( empty( $num_words ) ){
			$num_words = rand( 2, 10 );
		}

		// Pull random words
		for( $i = 1; $i <= $num_words; $i++ ){
			$title .= $random_words[ rand( 0, 31 ) ] . " ";
		}

		return apply_filters( "tc_title_data", substr( $title, 0, -1 ) );

	}


	/**
	 * Paragraphs full of random tags.
	 *
	 * Returns full TinyMCE-compatible paragraphs with random content such
	 * as tables, images, quotes, etc.
	 *
	 * @return string Paragraph(s) of text.
	 */
	public static function paragraphs(){

		$content = '';

		$random_content_types = array(
			"<p>OK, so images can get quite complicated as we have a few variables to work with! For example the image below has had a caption entered in the WordPress image upload dialog box, this creates a [caption] shortcode which then in turn wraps the whole thing in a <code>div</code> with inline styling! Maybe one day they'll be able to use the <code>figure</code> and <code>figcaption</code> elements for all this. Additionally, images can be wrapped in links which, if you're using anything other than <code>color</code> or <code>text-decoration</code> to style your links can be problematic.</p>",
			'<div id="attachment_28" class="wp-caption alignnone" style="width: 510px"><a href="#"><img src="http://www.wpfill.me.s3-website-us-east-1.amazonaws.com/img/img_large.png" alt="Your Alt Tag" title="bmxisbest" width="500" height="300" class="size-large wp-image-28"></a><p class="wp-caption-text">This is the optional caption.</p></div>',
			"<p>The next issue we face is image alignment, users get the option of <em>None</em>, <em>Left</em>, <em>Right</em> &amp; <em>Center</em>. On top of this, they also get the options of <em>Thumbnail</em>, <em>Medium</em>, <em>Large</em> &amp; <em>Fullsize</em>. You'll probably want to add floats to style the image position so important to remember to clear these to stop images popping below the bottom of your articles.</p>",
			"<table>
<thead>
<tr>
<th>Table Head Column One</th>
<th>Table Head Column Two</th>
<th>Table Head Column Three</th>
</tr>
</thead>
<tfoot>
<tr>
<td>Table Footer Column One</td>
<td>Table Footer Column Two</td>
<td>Table Footer Column Three</td>
</tr>
</tfoot>
<tbody>
<tr>
<td>Table Row Column One</td>
<td>Short Text</td>
<td>Testing a table cell with a longer amount of text to see what happens, you're not using tables for site layouts are you?</td>
</tr>
<tr>
<td>Table Row Column One</td>
<td>Table Row Column Two</td>
<td>Table Row Column Three</td>
</tr>
<tr>
<td>Table Row Column One</td>
<td>Table Row Column Two</td>
<td>Table Row Column Three</td>
</tr>
<tr>
<td>Table Row Column One</td>
<td>Table Row Column Two</td>
<td>Table Row Column Three</td>
</tr>
<tr>
<td>Table Row Column One</td>
<td>Table Row Column Two</td>
<td>Table Row Column Three</td>
</tr>
</tbody>
</table>",
			'<ol>
<li>Ordered list item one.</li>
<li>Ordered list item two.</li>
<li>Ordered list item three.</li>
<li>Ordered list item four.
	<ol>
		<li>Ordered list item one.</li>
		<li>Ordered list item two.
			<ol>
				<li>Ordered list item one.</li>
				<li>Ordered list item two.</li>
				<li>Ordered list item three.</li>
				<li>Ordered list item four.</li>
			</ol>
		</li>
		<li>Ordered list item three.</li>
		<li>Ordered list item four.</li>
	</ol>
</li>
<li>By the way, Wordpress does not let you create nested lists through the visual editor.</li>
</ol>
',
			'<ul>
<li>Unordered list item one.</li>
<li>Unordered list item two.</li>
	<ul>
		<li>Ordered list item one.</li>
		<li>Ordered list item two.
			<ul>
				<li>Ordered list item one.</li>
				<li>Ordered list item two.</li>
				<li>Ordered list item three.</li>
				<li>Ordered list item four.</li>
			</ul>
		</li>
		<li>Ordered list item three.</li>
		<li>Ordered list item four.</li>
	</ul>
<li>Unordered list item three.</li>
<li>Unordered list item four.</li>
<li>By the way, Wordpress does not let you create nested lists through the visual editor.</li>
</ul>',
			"<blockquote>
Currently WordPress blockquotes are just wrapped in blockquote tags and have no clear way for the user to define a source. Maybe one day they'll be more semantic (and easier to style) like the version below.
</blockquote>
<blockquote cite='http://html5doctor.com/blockquote-q-cite/'>
<p>HTML5 comes to our rescue with the footer element, allowing us to add semantically separate information about the quote.</p>
<footer>
<cite>
<a href='http://html5doctor.com/blockquote-q-cite/'>Oli Studholme, HTML5doctor.com</a>
</cite>
</footer>
</blockquote>",
			'<h1>Level One Heading</h1>
<h2>Level Two Heading</h2>
<h3>Level Three Heading</h3>
<h4>Level Four Heading</h4>
<h5>Level Five Heading</h5>
<h6>Level Six Heading</h6>',
			"<p>This is a standard paragraph created using the WordPress TinyMCE text editor. It has a <strong>strong tag</strong>, an <em>em tag</em> and a <del>strikethrough</del> which is actually just the del element. There are a few more inline elements which are not in the WordPress admin but we should check for incase your users get busy with the copy and paste. These include <cite>citations</cite>, <abbr title='abbreviation'>abbr</abbr>, bits of <code>code</code> and <var>variables</var>, <q>inline quotations</q>, <ins datetime='2011-12-08T20:19:53+00:00'>inserted text</ins>, text that is <s>no longer accurate</s> or something <mark>so important</mark> you might want to mark it. We can also style subscript and superscript characters like C0<sub>2</sub>, here is our 2<sup>nd</sup> example. If they are feeling non-semantic they might even use <b>bold</b>, <i>italic</i>, <big>big</big> or <small>small</small> elements too.&nbsp;Incidentally, these HTML4.01 tags have been given new life and semantic meaning in HTML5, you may be interested in reading this <a title='HTML5 Semantics' href='http://csswizardry.com/2011/01/html5-and-text-level-semantics'>article by Harry Roberts</a> which gives a nice excuse to test a link.&nbsp;&nbsp;It is also worth noting in the 'kitchen sink' view you can also add <span style='text-decoration: underline;'>underline</span>&nbsp;styling and set <span style='color: #ff0000;'>text color</span> with pesky inline CSS.</p>
<p style='text-align: left;'>Additionally, WordPress also sets text alignment with inline styles, like this left aligned paragraph.&nbsp;Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Cras mattis consectetur purus sit amet fermentum.</p>
<p style='text-align: right;'>This is a right aligned paragraph.&nbsp;Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Cras mattis consectetur purus sit amet fermentum.</p>
<p style='text-align: justify;'>This is a justified paragraph.&nbsp;Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Cras mattis consectetur purus sit amet fermentum.</p>
<p style='padding-left: 30px;'>Finally, you also have the option of an indented paragraph.&nbsp;Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Cras mattis consectetur purus sit amet fermentum.</p> <p>And last, and by no means least, users can also apply the <code>Address</code> tag to text like this:</p> <address>123 Example Street,
Testville,
West Madeupsburg,
CSSland,
1234</address> <p>...so there you have it, all our text elements</p>",
			'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean tincidunt luctus eros, a tincidunt massa aliquet sit amet. Sed faucibus, eros non lacinia porttitor, risus odio efficitur sapien, id porta urna massa ac est. Cras efficitur lacinia magna eget tempus. Fusce ex felis, finibus consectetur mi at, finibus rhoncus augue. In ut tortor lacinia, rutrum mauris vel, maximus tortor. Praesent ac arcu nec eros pharetra tristique. Morbi congue leo sed ipsum fermentum vulputate. Ut nulla eros, porta varius pulvinar eget, bibendum quis dolor. Morbi sed diam eu dui semper ornare nec quis nisl.',
			'Sed porttitor augue vitae ante posuere sodales iaculis nec neque. Etiam dapibus nulla id vulputate tempus. Quisque tempus nisi dui, a commodo nulla sodales ut. Nulla nec odio tempus, sodales diam quis, feugiat odio. Nulla tincidunt tincidunt turpis, eget cursus felis tempor lacinia. Aenean molestie libero ut erat luctus aliquam. Sed vel enim quis nisl lacinia posuere. Ut fringilla ligula ligula, nec rhoncus mi suscipit id. Praesent volutpat blandit felis, et suscipit elit vulputate sit amet. Morbi sit amet justo quis sem rutrum euismod. Pellentesque at dictum sem, sed condimentum ex. Vivamus massa nisi, convallis in semper sit amet, venenatis convallis lectus. Nunc tristique, ex ac rutrum vehicula, arcu ex efficitur justo, sed euismod ligula nulla ut purus.',
			'<table>
<tbody>
<tr>
<td>!</td>
<td>"</td>
<td>#</td>
<td>$</td>
<td>%</td>
<td>&amp;</td>
<td>\'</td>
<td>(</td>
<td>)</td>
<td>*</td>
</tr>
<tr>
<td>+</td>
<td>,</td>
<td>-</td>
<td>.</td>
<td>/</td>
<td>0</td>
<td>1</td>
<td>2</td>
<td>3</td>
<td>4</td>
</tr>
<tr>
<td>5</td>
<td>6</td>
<td>7</td>
<td>8</td>
<td>9</td>
<td>:</td>
<td>;</td>
<td>&gt;</td>
<td>=</td>
<td>&lt;</td>
</tr>
<tr>
<td>?</td>
<td>@</td>
<td>A</td>
<td>B</td>
<td>C</td>
<td>D</td>
<td>E</td>
<td>F</td>
<td>G</td>
<td>H</td>
</tr>
<tr>
<td>I</td>
<td>J</td>
<td>K</td>
<td>L</td>
<td>M</td>
<td>N</td>
<td>O</td>
<td>P</td>
<td>Q</td>
<td>R</td>
</tr>
<tr>
<td>S</td>
<td>T</td>
<td>U</td>
<td>V</td>
<td>W</td>
<td>X</td>
<td>Y</td>
<td>Z</td>
<td>[</td>
<td></td>
</tr>
<tr>
<td>]</td>
<td>^</td>
<td>_</td>
<td>`</td>
<td>a</td>
<td>b</td>
<td>c</td>
<td>d</td>
<td>e</td>
<td>f</td>
</tr>
<tr>
<td>g</td>
<td>h</td>
<td>i</td>
<td>j</td>
<td>k</td>
<td>l</td>
<td>m</td>
<td>n</td>
<td>o</td>
<td>p</td>
</tr>
<tr>
<td>q</td>
<td>r</td>
<td>s</td>
<td>t</td>
<td>u</td>
<td>v</td>
<td>w</td>
<td>x</td>
<td>y</td>
<td>z</td>
</tr>
<tr>
<td>{</td>
<td>|</td>
<td>}</td>
<td>~</td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
</tbody>
</table>',
			'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean tincidunt luctus eros, a tincidunt massa aliquet sit amet. Sed faucibus, eros non lacinia porttitor, risus odio efficitur sapien, id porta urna massa ac est. Cras efficitur lacinia magna eget tempus. Fusce ex felis, finibus consectetur mi at, finibus rhoncus augue. In ut tortor lacinia, rutrum mauris vel, maximus tortor. Praesent ac arcu nec eros pharetra tristique. Morbi congue leo sed ipsum fermentum vulputate. Ut nulla eros, porta varius pulvinar eget, bibendum quis dolor. Morbi sed diam eu dui semper ornare nec quis nisl.</p>

<p>Pellentesque gravida, mauris pellentesque facilisis viverra, odio quam auctor nunc, in lacinia odio ex vitae erat. Quisque posuere aliquet mi, id aliquet nulla malesuada ut. Nulla facilisi. Integer bibendum augue eget dapibus aliquam. In tempor, mauris in pharetra euismod, nunc metus fringilla metus, nec tincidunt lectus orci id justo. Nam semper risus a odio hendrerit suscipit. Curabitur dignissim, odio sed fringilla auctor, risus libero ullamcorper felis, et vestibulum neque ex a dolor. Suspendisse eu ullamcorper orci, id bibendum lacus. In at est sed ligula ullamcorper venenatis at vel ipsum. Integer libero justo, fermentum nec nisl non, bibendum bibendum tortor. Proin venenatis odio nec nisi facilisis, nec condimentum massa mollis. Donec efficitur libero quis congue aliquam. Duis sollicitudin vitae quam vitae pharetra.</p>

<p>Maecenas ultricies neque ut enim pharetra sodales. Etiam dolor sapien, commodo sed sollicitudin eget, porttitor quis lorem. Praesent euismod eros sed tortor sagittis, ut pretium ex vehicula. Nam ut magna et nunc vestibulum pulvinar. Vivamus tempor, ex eu cursus aliquam, tellus eros semper orci, id ultrices dui tellus commodo mauris. In mauris odio, lobortis id lectus in, tincidunt malesuada sem. Proin eu posuere metus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla auctor, odio ut euismod luctus, metus dolor molestie urna, iaculis rutrum nulla massa ac erat. Fusce suscipit eget odio ut tincidunt. Morbi vulputate aliquet placerat. Vivamus imperdiet posuere vehicula.</p>

<p>Nulla eu vestibulum metus. In leo lacus, vehicula at commodo eget, imperdiet vitae diam. Aliquam rutrum, massa eget pellentesque euismod, orci risus lobortis quam, et sollicitudin lectus augue quis nisi. Sed non justo at tellus mattis facilisis. Etiam feugiat sodales neque, at gravida lorem laoreet et. Donec convallis rhoncus sodales. Morbi erat mi, pulvinar quis ultrices a, luctus vel mauris. Aliquam vitae iaculis metus. Fusce tincidunt placerat nibh. Suspendisse lobortis libero massa, sit amet dapibus quam sodales eu.</p>

<p>In rhoncus mollis purus vitae ornare. Pellentesque nisi mauris, sodales vitae tortor sed, malesuada placerat massa. Integer eleifend imperdiet dolor at luctus. Donec ullamcorper dolor id auctor suscipit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sed ligula ipsum. Ut pulvinar iaculis volutpat. Phasellus dictum lorem non nulla pharetra, eget tincidunt justo ornare. Nulla dolor ligula, finibus id lectus vel, ornare porttitor diam. Nunc vehicula finibus commodo. Aliquam in commodo metus, a sodales libero. Donec vel dolor sed dolor ullamcorper fringilla in eu sapien. Phasellus lacinia lacus quis quam vestibulum, quis fringilla justo auctor. Integer quis ipsum porta, accumsan eros eget, pretium purus. Vestibulum eget leo tincidunt, porttitor urna a, vestibulum risus. Duis finibus neque sit amet nisi viverra, et vestibulum urna tincidunt.</p>

<p>Curabitur ligula magna, tempus eget ex sed, fringilla viverra justo. Nullam elit lacus, faucibus eget mi eget, posuere sagittis nisl. Sed tincidunt placerat tellus in porta. Morbi eu nibh ac lorem vehicula finibus vel a nunc. Donec iaculis leo quam, ac mattis massa ullamcorper quis. Suspendisse elementum sollicitudin augue ornare sollicitudin. Aenean laoreet orci non lectus hendrerit, ut pellentesque justo tempor. In hac habitasse platea dictumst. Ut et nibh et leo condimentum tempor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque sodales sed nulla at rutrum. Suspendisse quis pulvinar neque. Donec vestibulum, nunc id hendrerit placerat, nisl libero tristique nunc, sed semper mi nisl quis sapien. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Duis hendrerit fringilla tortor, interdum sodales sapien pharetra quis.</p>

<p>Vivamus sed neque molestie, lobortis elit in, laoreet augue. Etiam tincidunt sodales bibendum. Quisque ultrices, ante sed maximus dignissim, ex elit pharetra ex, a sagittis massa nisi eget massa. Aliquam dolor risus, tincidunt eu urna tincidunt, consectetur porttitor lacus. Fusce feugiat dolor ut efficitur elementum. Morbi auctor maximus rutrum. Pellentesque cursus est sed lacus consequat, vestibulum mattis urna imperdiet. Duis quis porta lectus. Quisque pulvinar ex at lacus mattis sollicitudin. Morbi gravida, leo et blandit fringilla, neque risus gravida elit, vel maximus sem magna id sapien.</p>',

		);

		$used_keys = array();
		for( $i = 1; $i < 7; $i++ ){

			// Pull a new random key and make sure we're not repeating any elements
			$key = rand( 0, 12 );
			while( in_array( $key, $used_keys ) ){
				$key = rand( 0, 12 );
			}

			$content .= $random_content_types[$key];

			$used_keys[] = $key;
		}

		return apply_filters( "tc_paragraphs_data", $content );

	}


	/**
	 * Plain text.
	 *
	 * Returns paragraphs of plain text.
	 *
	 * @return string Plain text paragraphs.
	 */
	public static function plain_text(){

		$paragraphs = array(
			'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean tincidunt luctus eros, a tincidunt massa aliquet sit amet. Sed faucibus, eros non lacinia porttitor, risus odio efficitur sapien, id porta urna massa ac est. Cras efficitur lacinia magna eget tempus. Fusce ex felis, finibus consectetur mi at, finibus rhoncus augue. In ut tortor lacinia, rutrum mauris vel, maximus tortor. Praesent ac arcu nec eros pharetra tristique. Morbi congue leo sed ipsum fermentum vulputate. Ut nulla eros, porta varius pulvinar eget, bibendum quis dolor. Morbi sed diam eu dui semper ornare nec quis nisl.',
			'Sed porttitor augue vitae ante posuere sodales iaculis nec neque. Etiam dapibus nulla id vulputate tempus. Quisque tempus nisi dui, a commodo nulla sodales ut. Nulla nec odio tempus, sodales diam quis, feugiat odio. Nulla tincidunt tincidunt turpis, eget cursus felis tempor lacinia. Aenean molestie libero ut erat luctus aliquam. Sed vel enim quis nisl lacinia posuere. Ut fringilla ligula ligula, nec rhoncus mi suscipit id. Praesent volutpat blandit felis, et suscipit elit vulputate sit amet. Morbi sit amet justo quis sem rutrum euismod. Pellentesque at dictum sem, sed condimentum ex. Vivamus massa nisi, convallis in semper sit amet, venenatis convallis lectus. Nunc tristique, ex ac rutrum vehicula, arcu ex efficitur justo, sed euismod ligula nulla ut purus.',
			'Maecenas ultricies neque ut enim pharetra sodales. Etiam dolor sapien, commodo sed sollicitudin eget, porttitor quis lorem. Praesent euismod eros sed tortor sagittis, ut pretium ex vehicula. Nam ut magna et nunc vestibulum pulvinar. Vivamus tempor, ex eu cursus aliquam, tellus eros semper orci, id ultrices dui tellus commodo mauris. In mauris odio, lobortis id lectus in, tincidunt malesuada sem. Proin eu posuere metus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla auctor, odio ut euismod luctus, metus dolor molestie urna, iaculis rutrum nulla massa ac erat. Fusce suscipit eget odio ut tincidunt. Morbi vulputate aliquet placerat. Vivamus imperdiet posuere vehicula.',
			'Pellentesque gravida, mauris pellentesque facilisis viverra, odio quam auctor nunc, in lacinia odio ex vitae erat. Quisque posuere aliquet mi, id aliquet nulla malesuada ut. Nulla facilisi. Integer bibendum augue eget dapibus aliquam. In tempor, mauris in pharetra euismod, nunc metus fringilla metus, nec tincidunt lectus orci id justo. Nam semper risus a odio hendrerit suscipit. Curabitur dignissim, odio sed fringilla auctor, risus libero ullamcorper felis, et vestibulum neque ex a dolor. Suspendisse eu ullamcorper orci, id bibendum lacus. In at est sed ligula ullamcorper venenatis at vel ipsum. Integer libero justo, fermentum nec nisl non, bibendum bibendum tortor. Proin venenatis odio nec nisi facilisis, nec condimentum massa mollis. Donec efficitur libero quis congue aliquam. Duis sollicitudin vitae quam vitae pharetra.',
			'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fringilla, est ac pulvinar pharetra, justo erat semper ante, sit amet blandit orci nibh nec nunc. Nam purus nibh, auctor lacinia imperdiet eget, bibendum et tellus. Fusce venenatis odio id nunc ultrices porta sed ut lorem. Aenean mollis neque quis nunc venenatis, sit amet tristique libero vulputate. Proin molestie dignissim ultricies. Aenean in iaculis ligula. Nunc sollicitudin, nisl non cursus suscipit, tortor nibh congue odio, quis facilisis leo ipsum auctor velit. In tincidunt imperdiet orci in vehicula. Nam tempus scelerisque sem quis aliquet. Pellentesque ipsum libero, dictum at leo eu, vulputate condimentum metus. Phasellus tincidunt nunc vel sem posuere placerat. Curabitur nec dolor et dui egestas pulvinar non vitae mauris. Sed convallis pellentesque sapien, sit amet tempus ex. Maecenas fringilla lobortis cursus.',
			'Nulla eu vestibulum metus. In leo lacus, vehicula at commodo eget, imperdiet vitae diam. Aliquam rutrum, massa eget pellentesque euismod, orci risus lobortis quam, et sollicitudin lectus augue quis nisi. Sed non justo at tellus mattis facilisis. Etiam feugiat sodales neque, at gravida lorem laoreet et. Donec convallis rhoncus sodales. Morbi erat mi, pulvinar quis ultrices a, luctus vel mauris. Aliquam vitae iaculis metus. Fusce tincidunt placerat nibh. Suspendisse lobortis libero massa, sit amet dapibus quam sodales eu.',
			'In rhoncus mollis purus vitae ornare. Pellentesque nisi mauris, sodales vitae tortor sed, malesuada placerat massa. Integer eleifend imperdiet dolor at luctus. Donec ullamcorper dolor id auctor suscipit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sed ligula ipsum. Ut pulvinar iaculis volutpat. Phasellus dictum lorem non nulla pharetra, eget tincidunt justo ornare. Nulla dolor ligula, finibus id lectus vel, ornare porttitor diam. Nunc vehicula finibus commodo. Aliquam in commodo metus, a sodales libero. Donec vel dolor sed dolor ullamcorper fringilla in eu sapien. Phasellus lacinia lacus quis quam vestibulum, quis fringilla justo auctor. Integer quis ipsum porta, accumsan eros eget, pretium purus. Vestibulum eget leo tincidunt, porttitor urna a, vestibulum risus. Duis finibus neque sit amet nisi viverra, et vestibulum urna tincidunt.',
			'Curabitur ligula magna, tempus eget ex sed, fringilla viverra justo. Nullam elit lacus, faucibus eget mi eget, posuere sagittis nisl. Sed tincidunt placerat tellus in porta. Morbi eu nibh ac lorem vehicula finibus vel a nunc. Donec iaculis leo quam, ac mattis massa ullamcorper quis. Suspendisse elementum sollicitudin augue ornare sollicitudin. Aenean laoreet orci non lectus hendrerit, ut pellentesque justo tempor. In hac habitasse platea dictumst. Ut et nibh et leo condimentum tempor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque sodales sed nulla at rutrum. Suspendisse quis pulvinar neque. Donec vestibulum, nunc id hendrerit placerat, nisl libero tristique nunc, sed semper mi nisl quis sapien. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Duis hendrerit fringilla tortor, interdum sodales sapien pharetra quis.',
			'Vivamus sed neque molestie, lobortis elit in, laoreet augue. Etiam tincidunt sodales bibendum. Quisque ultrices, ante sed maximus dignissim, ex elit pharetra ex, a sagittis massa nisi eget massa. Aliquam dolor risus, tincidunt eu urna tincidunt, consectetur porttitor lacus. Fusce feugiat dolor ut efficitur elementum. Morbi auctor maximus rutrum. Pellentesque cursus est sed lacus consequat, vestibulum mattis urna imperdiet. Duis quis porta lectus. Quisque pulvinar ex at lacus mattis sollicitudin. Morbi gravida, leo et blandit fringilla, neque risus gravida elit, vel maximus sem magna id sapien.',
			'Fusce semper erat tortor, at pulvinar risus luctus suscipit. Phasellus quis enim nisl. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas semper dapibus odio, nec pellentesque sem laoreet sit amet. Maecenas ut enim tellus. Fusce malesuada mattis sem, porta interdum ex fermentum quis. Ut eget quam mi. In molestie volutpat feugiat. Nulla vel viverra nunc. Integer lobortis nisl vitae placerat egestas. Curabitur tristique nulla at libero blandit, a eleifend augue tempus.',
		);

		return apply_filters( "tc_plain_text_data", $paragraphs[ rand( 0, 9 ) ] );

	}


	/**
	 * Image.
	 *
	 * Fetch a random image, make sure it is formatted right, download it, and
	 * put it in the media library.
	 *
	 * @see $this::get_image_link(), download_url, media_handle_sideload
	 *
	 * @param int $post_id Post ID.
	 * @return mixed Attachment ID or WP Error.
	 */
	public static function image( $post_id ){
		$file_array = array();

		// Get the image from the API
		$url = self::get_image_link();

		// If the returned string is empty or it's not a string, try again.
		if ( empty( $url ) || !is_string( $url ) ){

			// Try again
			$url = self::get_image_link();

			// If it fails again, just give up
			if ( empty( $url ) || !is_string( $url ) ){
				return;
			}

		}

		// Download the file
	    $tmp = \download_url( $url );

	    preg_match( '/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches );

        $file_array['name'] = basename( $matches[0] );
        $file_array['tmp_name'] = $tmp;

	    // Check for download errors
	    if ( is_wp_error( $tmp ) ) {
	        unlink( $file_array[ 'tmp_name' ] );
	        error_log( $tmp->get_error_message() );
	    }

		// Pull the image into the media library
	    $image_id = media_handle_sideload( $file_array, $post_id );

	    // Check for handle sideload errors.
	    if ( is_wp_error( $image_id ) ) {
	        unlink( $file_array['tmp_name'] );
	        error_log( $image_id->get_error_message() );
	    }

	    return apply_filters( "tc_image_data", $image_id );

	}


	/**
	 * Fetch an image url from the splashbase API.
	 *
	 * @see cURL functions, preg_match
	 *
	 * @return string Image URL.
	 */
	private static function get_image_link(){

		// cURL an image API for a completely random photo
		$curl = curl_init( "http://www.splashbase.co/api/v1/images/random?images_only=true" );

		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE );

		$curl_response = curl_exec( $curl );

		// If our cURL failed
		if ( $curl_response === false ) {
		    $info = curl_getinfo( $curl );
		    curl_close( $curl );
		    die( 'error occured during curl exec. Additional info: ' . var_export( $info ) );
		}

		curl_close( $curl );

		// Decode the data
		$response = json_decode( $curl_response, true );

		// Check to make sure that the return contains a valid image extensions
		preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $response['url'], $matches);

		if ( !empty( $matches ) ){
			return $response['url'];
		}

	}


	/**
	 * Date.
	 *
	 * Returns a date in the future (up to 60 days out) in the format prescribed.
	 *
	 * @param string $format PHP Date format.
	 * @return mixed Date in the format requested.
	 */
	public static function date( $format ){

		$num_days = rand( 1, 60 );
		$date = date( $format, strtotime( " +$num_days days" ) );

		return apply_filters( "tc_date_data", $date );

	}


	/**
	 * Time.
	 *
	 * Returns a random time in various formats
	 *
	 * @return string Time string
	 */
	public static function time(){

		$times = array(
			'8:00 am',
			'5:00PM',
			'13:00',
			'2015',
			date( 'G:i', strtotime( " +".rand( 4, 24 )." hours" ) ),
			date( 'g:i', strtotime( " +".rand( 4, 24 )." hours" ) ),
			date( 'G:i A', strtotime( " +".rand( 4, 24 )." hours" ) )
		);

		return apply_filters( "tc_time_data", $times[ rand( 0, 6 ) ] );

	}


	/**
	 * Timezone.
	 *
	 * Returns a random timezone from a subset of available options.
	 *
	 * @return string Timezone
	 */
	public static function timezone(){

		$timezones = array(
			'America/Denver',
			'America/New_York',
			'America/Los_Angeles',
			'Europe/London',
			'Europe/Paris',
			'Europe/Zurich',
			'Europe/Dublin',
			'Atlantic/Reykjavik',
			'Australia/Brisbane',
			'UTC+0',
			'UTC+7',
			'UTC-1',
			'UTC'
		);

		return apply_filters( "tc_timezone_data", $timezones[ rand( 0, 12 ) ] );

	}



	/**
	 * Phone.
	 *
	 * Returns a random phone # in multiple international formats.
	 *
	 * @return string Phone #.
	 */
	public static function phone(){

		$phone_numbers = array(
			'7203893101',
			'303-555-1251',
			'(720) 895 0969',
			'(303)-278-2078',
			'1-907-486-1102',
			'011-44-871-789-3642',
			'1-800-437-7950',
			'1-503-254-1000',
			'1-845-354-9912',
			'+1 253-243-3381',
			'+43 780 0047112'
		);

		return apply_filters( "tc_phone_data", $phone_numbers[ rand( 0, 10 ) ] );

	}


	/**
	 * Email.
	 *
	 * Returns a random email address in random lengths/formats.
	 *
	 * @return string Email address.
	 */
	public static function email( $superrandom = false ){

		// In certain situations we need to ensure that the email is never
		// duplicated, like in creating new users.
		if ( $superrandom !== false ){
			$user = $domain = '';

			$tlds = array(
				"com",
				"net",
				"gov",
				"org",
				"edu",
				"biz",
				"info"
			);

			$char = "0123456789abcdefghijklmnopqrstuvwxyz";

			$user_length = mt_rand( 5, 20 );
		    $domain_length = mt_rand( 7, 12 );

			for ( $i = 1; $i <= $user_length; $i++ ){
				$user .= substr( $char, mt_rand( 0, strlen( $char ) ), 1 );
			}

			for ( $i = 1; $i <= $domain_length; $i++ ){
				$domain .= substr( $char, mt_rand( 0, strlen( $char ) ), 1 );
			}

			$tld = $tlds[ mt_rand( 0, ( sizeof( $tlds ) - 1 ) ) ];

			$email = $user . "@" . $domain . '.' . $tld;

		} else {

			$email_addresses = array(
				'mike@oldtownmediainc.com',
				'me@me.com',
				'joe@smith.org+15',
				'jane@janedoe.com',
				'help@github.com',
				'brian_roberts@comcast.com',
				'inigo@iaminigomontoyayoukilledmyfatherpreparetodie.com',
				'witch@theyellowbrickroad.com'
			);

			$email = $email_addresses[ rand( 0, 7 ) ];

		}


		return apply_filters( "tc_email_data", $email );

	}


	/**
	 * Link.
	 *
	 * Returns link in a completely random format.
	 *
	 * @see site_url
	 *
	 * @return string URL.
	 */
	public static function link(){

		$links = array(
			'http://google.com',
			'https://www.twitter.com',
			site_url( '/?iam=anextravariable' ),
			'github.com',
			'http://filebase.com',
			'www.oldtownmediainc.com',
			'http://facebook.com',
			'https://www.eff.org'
		);

		return apply_filters( "tc_link_data", $links[ rand( 0, 7 ) ] );

	}

	/**
	 * Oembed.
	 *
	 * Returns a random oembed-compatible link.
	 *
	 * @return string URL.
	 */
	public static function oembed(){

		$links = array(
			'https://www.youtube.com/watch?v=A85-YQsm6pY',
			'https://vimeo.com/140327103',
			'https://twitter.com/WordPress/status/664594697093009408',
			'https://embed-ssl.ted.com/talks/regina_hartley_why_the_best_hire_might_not_have_the_perfect_resume.html',
			'http://www.slideshare.net/laurengalanter/choose-your-own-career-adventure',
			'https://www.instagram.com/p/-eyLo0RMfX',
		);

		return apply_filters( "tc_oembed_data", $links[ rand( 0, 5 ) ] );

	}


	/**
	 * Video Link.
	 *
	 * Returns a video link from the service of your choice (if that service is
	 * YouTube or Vimeo :) ).
	 *
	 * @param	string $type Video service to get link from
	 * @return	string URL.
	 */
	public static function video( $type ){

		// Switch through our video types. Expecting to add more in the future
		switch( $type ){

			// YouTube videos
			case 'youtube' :
				$links = array(
					'https://www.youtube.com/watch?v=tntOCGkgt98',
					'https://www.youtube.com/watch?v=O1KW3ZkLtuo',
					'https://www.youtube.com/watch?v=G8KpPw303PY',
					'https://www.youtube.com/watch?v=HxM46vRJMZs',
					'https://www.youtube.com/watch?v=nRzsgCp60YU',
					'https://www.youtube.com/watch?v=25OUFtdno8U',
					'https://www.youtube.com/watch?v=PHAc3_MEjgQ',
					'https://www.youtube.com/watch?v=9bZkp7q19f0',
					'https://www.youtube.com/watch?v=_OBlgSz8sSM',
				);

				break;

			// Vimeo videos
			case 'vimeo' :
				$links = array(
					'https://vimeo.com/156161909',
					'https://vimeo.com/156045670',
					'https://vimeo.com/144698619',
					'https://vimeo.com/151799633',
					'https://vimeo.com/149224063',
					'https://vimeo.com/154915431',
					'https://vimeo.com/155404383',
					'https://vimeo.com/149478317',
					'https://vimeo.com/154698227',
				);

				break;

			// Fallback
			default:

				$links = array();

		}

		return apply_filters( "tc_video_data", $links[ rand( 0, 8 ) ] );

	}

	/**
	 * Name function.
	 *
	 * Makes a random name.
	 *
	 * @return array Randomly strung together name.
	 */
	public static function name(){

		$first_names = array(
			'Jacqui',
			'Buffy',
			'Teddy',
			'Cindie',
			'Carroll',
			'Karly',
			'Maricela',
			'Kittie',
			'Jetta',
			'Denise',
			'Guillermo',
			'Domingo',
			'Benjamin',
			'Olga',
			'Shane',
			'Bessie',
			'Jose',
			'Damon',
			'Rodolfo',
			'George',
		);

		$last_names = array(
			'Henley',
			'Trask',
			'Dick',
			'Irby',
			'Raley',
			'Bland',
			'Rossi',
			'Gunther',
			'Mchenry',
			'Isaacs',
			'Romero',
			'Mcbride',
			'Armstrong',
			'Mccoy',
			'Evans',
			'Dennis',
			'Swanson',
			'Estrada',
			'Johnston',
			'Graves',
		);

		$name = array(
			'first'	=> $first_names[ rand( 0, 19 ) ],
			'last'	=> $last_names[ rand( 0, 19 ) ]
		);

		return apply_filters( "tc_name_data", $name );

	}

}
