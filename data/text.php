<?php

namespace DummyBot\Data;

use DummyBot\Abstracts;

class Text extends Abstracts\Data {
	protected function data( $field ) {

	}

	protected function text() {
		$random_words = [
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
			'dis',
		];

		$num_words = rand( 2, 10 );

		// Pull random words
		for( $i = 1; $i <= $num_words; $i++ ) {
			$words[] = $this->random( $random_words );
		}

		return join( ' ', $words );
	}

	protected function short( $field ) {
		$text = $this->text( $field );

		$words = explode( ' ', $words );

		if ( count( $words ) > 5 ) {
			$words = array_slice( $words, 0, 4 );
		}

		return join( ' ', $words );
	}

	protected function paragraphs( $field ) {
		$data = [
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
		];

		return $this->random( $data );
	}

	protected function code( $field ) {

	}
}
