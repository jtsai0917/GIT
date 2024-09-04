!DOCTYPE html>
<html>

	<head>
		<meta charset="big5">
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1,minimum-scale=1,user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title></title>
		<script src="/css/jquery-1.12.4.js"></script>
  <script src="/css/jquery-ui.js"></script>
		<link rel="stylesheet" type="text/css" href="css/choujiang.css"/>
		<style type="text/css">
			
		</style>
	</head>

	<body>
		<div class="light">
			<div class="num">
				您今日抽獎機會還有<span class="red">3</span>次
			</div>
			<div class="gift_div">
				<div class="gift gift1">獎品1</div>
				<div class="gift gift2">獎品2</div>
				<div class="gift gift3">獎品3</div>
				<div class="gift gift4">獎品4</div>
				<div class="gift gift5">獎品5</div>
				<div class="gift gift6">獎品6</div>
				<div class="gift gift7">獎品7</div>
				<div class="gift gift8">獎品8</div>
				<div class="start">開始抽獎</div>
			</div>
		</div>
	</body>
<script src="js/jquery-2.1.1.min.js" type="text/javascript" charset="utf-8"></script>
<script src="js/choujiang.js" type="text/javascript" charset="utf-8"></script>
</html>

<!--css部分-->
* {
	margin: 0;
	padding: 0;
}

.light {
	width: 100%;
	height: 7.6rem;
	background: #BD1D25;
	padding: .2rem;
	box-sizing: border-box;
	font-size: .24rem;
}

.light .gift_div {
	width: 100%;
	height: 6.4rem;
	background: #139365;
	border-radius: .1rem;
	position: relative;
	padding: .05rem .5%;
	box-sizing: border-box;
	margin-top: .2rem;
}

.gift_div>div {
	position: absolute;
	width: 32%;
	height: 2rem;
	margin: .05rem .5%;
	background: #E6F0EC;
	border-radius: .06rem;
}
.gift2,
.gift6,
.start{
	left: 33.5%;
}
.gift3,
.gift4,
.gift5{
	right: .5%;
}
.gift4,
.gift8,
.start{
	top: 2.15rem;
}
.gift5,
.gift6,
.gift7{
	bottom: .05rem;
}
.gift_div .start{
	background: #FDB827;
	text-align: center;
	line-height: 2rem;
	color: #FF001F;
}
.red{
	color: red;
}
.num{
	text-align: center;
	font-size: .32rem;
	line-height: .6rem;
	background: #E6EFEC;
	border-radius: .6rem;
}
.gift_b:after{
	position: absolute;
	width: 100%;
	height: 100%;
	background: rgba(0,0,0,.6);
	content: '';
	left: 0;
}
<!--js部分-->
$(function() {
	var speed = 150, //跑馬燈速度
		click = true, //阻止多次點擊
		img_index = -1, //陰影停在當前獎品的序號
		circle = 0, //跑馬燈跑了多少次
		maths,//取一個隨機數;
		num=$('.red').text();

	$('.start').click(function() {
		if(click&&num>0) {
			click = false;
			maths = parseInt((Math.random() * 10) + 80);
			light();
		} else {
			return false;
		}
	});

	function light() {
		img();
		circle++;
		var timer = setTimeout(light, speed);
		if(circle > 0 && circle < 5) {
			speed -= 10;
		} else if(circle > 5 && circle < 20) {
			speed -= 5;
		} else if(circle > 50 && circle < 70) {
			speed += 5
		} else if(circle > 70 && circle < maths) {
			speed += 10
		} else if(circle == maths) {
			var text = $('.gift_div .gift:eq(' + img_index + ')').text();
			console.log(circle + maths, 'aaa', img_index, $('.gift_div .gift:eq(' + img_index + ')').text())
			clearTimeout(timer);

			setTimeout(function() {
				alert('恭喜獲得' + text)
			}, 300)
			click = true;
			speed = 150;
			circle = 0;
			img_index = -1;
			num--;
			$('.red').text(num)
		}
	}

	function img() {
		if(img_index < 7) {
			img_index++;
		} else if(img_index == 7) {
			img_index = 0;
		}
		$('.gift_div .gift:eq(' + img_index + ')').addClass('gift_b').siblings().removeClass('gift_b');
	}

});