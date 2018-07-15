<?php
	include_once("header.php");
	include_once("left_menu.php");
    
    $adminSettings= getAppSettings("",true);
    $sidebarColor = $adminSettings['sidebar_color'];
    $colors = array("purple"=>"#9368E9","blue"=>"#1F77D0","azure"=>"#1DC7EA","green"=>"#87CB16","orange"=>"#FFA534","red"=>"#FB404B");
    $sidebarColor = $colors[$adminSettings['sidebar_color']];
?>
<style>
.delay_table tr td{
	padding:5px !important;
}

.widget{
    background:<?php echo $sidebarColor; ?> !important;
}
 
</style>
<div class="main-panel">
	<?php include_once('navbar.php');?>
    
    

<?php
function parse_for_js($str){
    $str=str_replace("\\","",$str);
    $str=str_replace("'","",$str);
    $str=str_replace('"','',$str);
    $str=str_replace("\t",'',$str);
    $str=trim(json_encode($str),'"');
    return $str;
}


$sql_campaigns_list=mysqli_query($link,"select * from campaigns where user_id='$_SESSION[user_id]'"); 
    $arr_campaigns="";
    while($fetch_campaigns_list=mysqli_fetch_assoc($sql_campaigns_list)){

        $arr_campaigns.='"'.$fetch_campaigns_list['id'].'":"'.parse_for_js($fetch_campaigns_list['title']).'",'; 
         
    }
    $arr_campaigns='{'.trim($arr_campaigns,",").'}';
    $arr_campaigns_json=$arr_campaigns;
$arr_campaigns=json_decode($arr_campaigns_json,true); 


$sql_pages_list=mysqli_query($link,"select * from pages where created_user='$_SESSION[user_id]'"); 
    $arr_pages="";
    while($fetch_pages_list=mysqli_fetch_assoc($sql_pages_list)){

        $arr_pages.='"'.$fetch_pages_list['id'].'":"'.parse_for_js($fetch_pages_list['page_title']).'",'; 
         
    }
    $arr_pages='{'.trim($arr_pages,",").'}';
    $arr_pages_json=$arr_pages;
$arr_pages=json_decode($arr_pages_json,true); 
 $page_key="";
 
if(isset($_GET['id']))
{
$sql="select * from pages where id='$_GET[id]' and created_user='$_SESSION[user_id]'";
$res=mysqli_query($link,$sql);
$row=mysqli_fetch_assoc($res);
 $json=$row['json'];
   $obj=json_decode($json,true); 
   if($obj === NULL){
   $str=str_replace('"{','{',parse(DBoutf($row['json']))); 
$json=str_replace('}"','}',$str);
$obj=json_decode($json,true);
   } 
?>
<?php 
$page_key=$row['page_key'];
}
else
{
 $page_key=uniqid();   
}

function DBoutf($string)
{
$string = stripslashes(trim($string));
    return html_entity_decode($string,ENT_QUOTES);
}
    function parse($text) {
    // Damn pesky carriage returns...
    $text = str_replace("\r\n", "\n", $text);
    $text = str_replace("\r", "\n", $text);

    // JSON requires new line characters be escaped
    $text = str_replace("\n", " ", $text);
    return $text;
}
$fonts_json='{"family_0": "Open Sans","family_1": "Oswald","family_2": "Droid Sans","family_3": "Open Sans Condensed","family_4": "Droid Serif","family_5": "Yanone Kaffeesatz","family_6": "Ubuntu","family_7": "PT Sans","family_8": "PT Sans Narrow","family_9": "Lato","family_10": "Arvo","family_11": "Nunito","family_12": "Lora","family_13": "Lobster","family_14": "Exo","family_15": "Francois One","family_16": "Play","family_17": "Rokkitt","family_18": "Unkempt","family_19": "Source Sans Pro","family_20": "Merriweather","family_21": "Changa One","family_22": "PT Serif","family_23": "The Girl Next Door","family_24": "Cabin","family_25": "Arimo","family_26": "Cuprum","family_27": "Bitter","family_28": "Vollkorn","family_29": "Questrial","family_30": "Coming Soon","family_31": "Anton","family_32": "Josefin Sans","family_33": "Raleway","family_34": "Ubuntu Condensed","family_35": "Cantarell","family_36": "Abel","family_37": "Shadows Into Light","family_38": "Dosis","family_39": "Crafty Girls","family_40": "Dancing Script","family_41": "Signika","family_42": "Fredoka One","family_43": "Nobile","family_44": "Pacifico","family_45": "Philosopher","family_46": "Metamorphous","family_47": "Chewy","family_48": "Asap","family_49": "Righteous","family_50": "Black Ops One","family_51": "Squada One","family_52": "Calligraffitti","family_53": "Orienta","family_54": "Luckiest Guy","family_55": "Kreon","family_56": "Montserrat","family_57": "Cherry Cream Soda","family_58": "Maven Pro","family_59": "Comfortaa","family_60": "Molengo","family_61": "Limelight","family_62": "Quicksand","family_63": "Rock Salt","family_64": "Muli","family_65": "Cabin Condensed","family_66": "Tangerine","family_67": "Crimson Text","family_68": "News Cycle","family_69": "Sanchez","family_70": "Reenie Beanie","family_71": "Permanent Marker","family_72": "Droid Sans Mono","family_73": "Josefin Slab","family_74": "Amaranth","family_75": "Chivo","family_76": "Paytone One","family_77": "PT Sans Caption","family_78": "Marvel","family_79": "Pontano Sans","family_80": "Playfair Display","family_81": "Bree Serif","family_82": "Marck Script","family_83": "Judson","family_84": "Gloria Hallelujah","family_85": "Istok Web","family_86": "Spirax","family_87": "Covered By Your Grace","family_88": "Anaheim","family_89": "Lobster Two","family_90": "Syncopate","family_91": "Oxygen","family_92": "Crushed","family_93": "Anonymous Pro","family_94": "Walter Turncoat","family_95": "Nothing You Could Do","family_96": "Just Me Again Down Here","family_97": "Happy Monkey","family_98": "Goudy Bookletter 1911","family_99": "Schoolbell","family_100": "Homemade Apple","family_101": "Jockey One","family_102": "Old Standard TT","family_103": "Indie Flower","family_104": "Copse","family_105": "Varela Round","family_106": "Leckerli One","family_107": "Amatic SC","family_108": "Waiting for the Sunrise","family_109": "Cardo","family_110": "Inconsolata","family_111": "Gentium Book Basic","family_112": "Architects Daughter","family_113": "Bevan","family_114": "Maiden Orange","family_115": "Karla","family_116": "Kristi","family_117": "Bangers","family_118": "Boogaloo","family_119": "Sunshiney","family_120": "Fontdiner Swanky","family_121": "Voltaire","family_122": "Gudea","family_123": "Crete Round","family_124": "Quattrocento Sans","family_125": "Tinos","family_126": "PT Serif Caption","family_127": "Allerta","family_128": "Varela","family_129": "Share","family_130": "Just Another Hand","family_131": "Neucha","family_132": "Didact Gothic","family_133": "Electrolize","family_134": "Puritan","family_135": "Patua One","family_136": "Redressed","family_137": "Orbitron","family_138": "Shadows Into Light Two","family_139": "Slackey","family_140": "Patrick Hand","family_141": "Rochester","family_142": "Rosario","family_143": "Coda","family_144": "EB Garamond","family_145": "Hammersmith One","family_146": "Ropa Sans","family_147": "Doppio One","family_148": "Actor","family_149": "Allerta Stencil","family_150": "Carme","family_151": "Special Elite","family_152": "Neuton","family_153": "Kameron","family_154": "Poiret One","family_155": "Jura","family_156": "Overlock","family_157": "Love Ya Like A Sister","family_158": "Economica","family_159": "Quattrocento","family_160": "Pinyon Script","family_161": "Oleo Script","family_162": "Cantata One","family_163": "Brawler","family_164": "Telex","family_165": "Handlee","family_166": "Fanwood Text","family_167": "Numans","family_168": "Alfa Slab One","family_169": "Ubuntu Mono","family_170": "Archivo Narrow","family_171": "Mako","family_172": "Metrophobic","family_173": "Viga","family_174": "Short Stack","family_175": "Shanti","family_176": "Mate SC","family_177": "Mountains of Christmas","family_178": "Carter One","family_179": "Gentium Basic","family_180": "Cabin Sketch","family_181": "Merienda One","family_182": "Sorts Mill Goudy","family_183": "Passion One","family_184": "Yellowtail","family_185": "Six Caps","family_186": "Annie Use Your Telescope","family_187": "Satisfy","family_188": "IM Fell English","family_189": "Coustard","family_190": "Kranky","family_191": "Contrail One","family_192": "Trocchi","family_193": "Tienne","family_194": "Loved by the King","family_195": "Gruppo","family_196": "Titillium Web","family_197": "MedievalSharp","family_198": "Michroma","family_199": "Montserrat Alternates","family_200": "Bentham","family_201": "Fredericka the Great","family_202": "Lekton","family_203": "Give You Glory","family_204": "Glegoo","family_205": "Homenaje","family_206": "Inder","family_207": "Arbutus Slab","family_208": "Norican","family_209": "Sansita One","family_210": "Damion","family_211": "Berkshire Swash","family_212": "Days One","family_213": "Prata","family_214": "Pompiere","family_215": "Sue Ellen Francisco","family_216": "Alice","family_217": "Gochi Hand","family_218": "Vidaloka","family_219": "Cedarville Cursive","family_220": "Sancreek","family_221": "Stardos Stencil","family_222": "Cousine","family_223": "Bowlby One SC","family_224": "Russo One","family_225": "Delius","family_226": "Convergence","family_227": "Magra","family_228": "Rancho","family_229": "Andika","family_230": "Kaushan Script","family_231": "Poly","family_232": "IM Fell DW Pica","family_233": "Antic","family_234": "Enriqueta","family_235": "Chau Philomene One","family_236": "Podkova","family_237": "Cookie","family_238": "Delius Unicase","family_239": "Alike","family_240": "Volkhov","family_241": "Aldrich","family_242": "Alegreya","family_243": "Italianno","family_244": "Signika Negative","family_245": "Ultra","family_246": "Spinnaker","family_247": "BenchNine","family_248": "Bilbo","family_249": "Salsa","family_250": "Nixie One","family_251": "Corben","family_252": "Source Code Pro","family_253": "Arapey","family_254": "Wire One","family_255": "Adamina","family_256": "Dawning of a New Day","family_257": "Megrim","family_258": "Forum","family_259": "Radley","family_260": "Courgette","family_261": "Cutive","family_262": "UnifrakturMaguntia","family_263": "Allan","family_264": "Noticia Text","family_265": "Coda Caption","family_266": "IM Fell DW Pica SC","family_267": "IM Fell English SC","family_268": "Aclonica","family_269": "Miltonian Tattoo","family_270": "Cantora One","family_271": "Abril Fatface","family_272": "Geo","family_273": "Caudex","family_274": "Lovers Quarrel","family_275": "Delius Swash Caps","family_276": "Codystar","family_277": "Capriola","family_278": "Baumans","family_279": "Italiana","family_280": "Kotta One","family_281": "Parisienne","family_282": "Swanky and Moo Moo","family_283": "Candal","family_284": "Federo","family_285": "Advent Pro","family_286": "Lemon","family_287": "Great Vibes","family_288": "Lustria","family_289": "Snippet","family_290": "Lusitana","family_291": "Press Start 2P","family_292": "Rammetto One","family_293": "Krona One","family_294": "La Belle Aurore","family_295": "Rationale","family_296": "Nova Round","family_297": "Audiowide","family_298": "Unna","family_299": "Playball","family_300": "Montez","family_301": "IM Fell Great Primer SC","family_302": "Vibur","family_303": "Fugaz One","family_304": "Kite One","family_305": "Tenor Sans","family_306": "Gorditas","family_307": "Expletus Sans","family_308": "IM Fell French Canon SC","family_309": "Ovo","family_310": "Ruda","family_311": "Allura","family_312": "Tulpen One","family_313": "Andada","family_314": "Meddon","family_315": "Holtwood One SC","family_316": "Acme","family_317": "League Script","family_318": "Niconne","family_319": "Nova Script","family_320": "Carrois Gothic","family_321": "Astloch","family_322": "Quantico","family_323": "Over the Rainbow","family_324": "Irish Grover","family_325": "Kelly Slab","family_326": "Mr Dafoe","family_327": "Bowlby One","family_328": "Dorsa","family_329": "Gravitas One","family_330": "Dynalight","family_331": "Artifika","family_332": "Archivo Black","family_333": "UnifrakturCook","family_334": "Julee","family_335": "Vast Shadow","family_336": "Nova Square","family_337": "Buda","family_338": "Bad Script","family_339": "Zeyada","family_340": "VT323","family_341": "Marmelad","family_342": "Nova Slim","family_343": "IM Fell Great Primer","family_344": "Mate","family_345": "IM Fell French Canon","family_346": "Yeseva One","family_347": "IM Fell Double Pica SC","family_348": "Oranienbaum","family_349": "Kenia","family_350": "IM Fell Double Pica","family_351": "Asset","family_352": "Nova Oval","family_353": "Petit Formal Script","family_354": "Petrona","family_355": "ABeeZee","family_356": "Scada","family_357": "Fjord One","family_358": "Ruslan Display","family_359": "Nova Mono","family_360": "Average","family_361": "Prociono","family_362": "Alex Brush","family_363": "Sofia","family_364": "Linden Hill","family_365": "Geostar","family_366": "Voces","family_367": "Hanuman","family_368": "Nova Flat","family_369": "Sniglet","family_370": "Smythe","family_371": "Antic Slab","family_372": "Buenard","family_373": "Monoton","family_374": "Monofett","family_375": "Condiment","family_376": "Bigshot One","family_377": "Cagliostro","family_378": "Armata","family_379": "Lancelot","family_380": "Eater","family_381": "Wallpoet","family_382": "Qwigley","family_383": "Modern Antiqua","family_384": "Miltonian","family_385": "Alegreya SC","family_386": "Sigmar One","family_387": "Lilita One","family_388": "Port Lligat Sans","family_389": "GFS Neohellenic","family_390": "Alike Angular","family_391": "Imprima","family_392": "Piedra","family_393": "Nova Cut","family_394": "Average Sans","family_395": "Poller One","family_396": "Goblin One","family_397": "Knewave","family_398": "Miniver","family_399": "Belgrano","family_400": "Graduate","family_401": "Share Tech","family_402": "Oxygen Mono","family_403": "Geostar Fill","family_404": "Passero One","family_405": "Ruluko","family_406": "Chelsea Market","family_407": "Supermercado One","family_408": "Smokum","family_409": "Federant","family_410": "Atomic Age","family_411": "Euphoria Script","family_412": "Aubrey","family_413": "Mystery Quest","family_414": "Galdeano","family_415": "GFS Didot","family_416": "Duru Sans","family_417": "Shojumaru","family_418": "Rosarivo","family_419": "Ranchers","family_420": "Esteban","family_421": "Oregano","family_422": "Headland One","family_423": "PT Mono","family_424": "Aladin","family_425": "Montaga","family_426": "Oldenburg","family_427": "Strait","family_428": "Amethysta","family_429": "Marcellus SC","family_430": "Jolly Lodger","family_431": "Julius Sans One","family_432": "Cambo","family_433": "Rambla","family_434": "Trochut","family_435": "Concert One","family_436": "Creepster","family_437": "Averia Sans Libre","family_438": "Stint Ultra Condensed","family_439": "Trade Winds","family_440": "Wellfleet","family_441": "Khmer","family_442": "Rouge Script","family_443": "Basic","family_444": "Londrina Solid","family_445": "Margarine","family_446": "Engagement","family_447": "Rye","family_448": "Ruthie","family_449": "Sail","family_450": "Quando","family_451": "Henny Penny","family_452": "Mr De Haviland","family_453": "Junge","family_454": "Finger Paint","family_455": "Flamenco","family_456": "Yesteryear","family_457": "Titan One","family_458": "Port Lligat Slab","family_459": "Suwannaphum","family_460": "Overlock SC","family_461": "Carrois Gothic SC","family_462": "Averia Libre","family_463": "Sevillana","family_464": "Londrina Sketch","family_465": "McLaren","family_466": "Revalia","family_467": "Arizonia","family_468": "Bubblegum Sans","family_469": "Medula One","family_470": "Playfair Display SC","family_471": "Mrs Saint Delafield","family_472": "Erica One","family_473": "Simonetta","family_474": "Skranji","family_475": "Iceland","family_476": "Ledger","family_477": "Emilys Candy","family_478": "Belleza","family_479": "Aguafina Script","family_480": "Paprika","family_481": "Trykker","family_482": "Averia Serif Libre","family_483": "Fenix","family_484": "Emblema One","family_485": "Cinzel","family_486": "Butterfly Kids","family_487": "Fresca","family_488": "Cinzel Decorative","family_489": "Racing Sans One","family_490": "Stoke","family_491": "Cherry Swash","family_492": "Koulen","family_493": "Glass Antiqua","family_494": "Life Savers","family_495": "Raleway Dots","family_496": "Iceberg","family_497": "Marcellus","family_498": "Bilbo Swash Caps","family_499": "Londrina Outline","family_500": "Felipa","family_501": "Almendra","family_502": "Balthazar","family_503": "Spicy Rice","family_504": "Amarante","family_505": "Antic Didone","family_506": "Eagle Lake","family_507": "Mrs Sheppards","family_508": "Prosto One","family_509": "Molle","family_510": "Inika","family_511": "Ruge Boogie","family_512": "Chicle","family_513": "Ribeye Marrow","family_514": "Asul","family_515": "Sonsie One","family_516": "Stint Ultra Expanded","family_517": "Princess Sofia","family_518": "Uncial Antiqua","family_519": "Merienda","family_520": "Gafata","family_521": "Moul","family_522": "Grand Hotel","family_523": "Chela One","family_524": "Averia Gruesa Libre","family_525": "Frijole","family_526": "Macondo Swash Caps","family_527": "Sirin Stencil","family_528": "Monsieur La Doulaise","family_529": "Almendra SC","family_530": "Battambang","family_531": "Habibi","family_532": "Englebert","family_533": "Original Surfer","family_534": "Griffy","family_535": "Della Respira","family_536": "Marko One","family_537": "Offside","family_538": "Seaweed Script","family_539": "Cutive Mono","family_540": "Jacques Francois","family_541": "Devonshire","family_542": "Dr Sugiyama","family_543": "Ribeye","family_544": "Nosifer","family_545": "Seymour One","family_546": "Unica One","family_547": "Peralta","family_548": "Macondo","family_549": "Caesar Dressing","family_550": "Nokora","family_551": "Sacramento","family_552": "Rum Raisin","family_553": "Chango","family_554": "Fondamento","family_555": "Autour One","family_556": "Text Me One","family_557": "Clicker Script","family_558": "Flavors","family_559": "Londrina Shadow","family_560": "Mouse Memoirs","family_561": "Oleo Script Swash Caps","family_562": "Monda","family_563": "Libre Baskerville","family_564": "Ewert","family_565": "Germania One","family_566": "Jim Nightshade","family_567": "Underdog","family_568": "Gilda Display","family_569": "Croissant One","family_570": "Metal Mania","family_571": "Sarina","family_572": "Fascinate","family_573": "Keania One","family_574": "Bonbon","family_575": "Rufina","family_576": "Sofadi One","family_577": "Montserrat Subrayada","family_578": "Plaster","family_579": "Unlock","family_580": "Arbutus","family_581": "Ceviche One","family_582": "Share Tech Mono","family_583": "Bubbler One","family_584": "Combo","family_585": "Meie Script","family_586": "Domine","family_587": "Romanesco","family_588": "Freehand","family_589": "Content","family_590": "Diplomata","family_591": "Butcherman","family_592": "Quintessential","family_593": "Galindo","family_594": "Herr Von Muellerhoff","family_595": "Angkor","family_596": "Diplomata SC","family_597": "Mr Bedfort","family_598": "Dangrek","family_599": "Miss Fajardose","family_600": "Freckle Face","family_601": "Milonga","family_602": "Bayon","family_603": "Taprom","family_604": "Akronim","family_605": "Stalemate","family_606": "New Rocker","family_607": "Faster One","family_608": "Jacques Francois Shadow","family_609": "Fascinate Inline","family_610": "Purple Purse","family_611": "Joti One","family_612": "Stalinist One","family_613": "Snowburst One","family_614": "Pirata One","family_615": "Vampiro One","family_616": "Metal","family_617": "Chenla","family_618": "Warnes","family_619": "Bigelow Rules","family_620": "Hanalei Fill","family_621": "Bokor","family_622": "Risque","family_623": "Siemreap","family_624": "Preahvihear","family_625": "Moulpali","family_626": "Almendra Display","family_627": "Odor Mean Chey","family_628": "Hanalei","family_629": "Fasthand"}';
$limitt=11;
$arr_button_type=ARRAY("label"=>"Use Label","button_image"=>"Upload Image","button_template"=>"Use Template");
$arr_align=ARRAY("center"=>"Center","left"=>"Left","right"=>"Right");
$arr_force_email=ARRAY("none"=>"Select Any","aweber"=>"Aweber","mailchimp"=>"Mail Chimp","icontact"=>"I Contact","const_contact"=>"Constant Contact","getresponse"=>"Get Response","sendreach"=>"Sendreach");
$arr_simple_button_options=ARRAY("bg_color"=>"Use Background Color","bg_image"=>"Use Background Image","image"=>"Use Only Image");
$arr_wbsms_settings=ARRAY("wbsms_user"=>@$fetch_app_info_main['wbsms_user'],"wbsms_pass"=>@$fetch_app_info_main['wbsms_pass'],"wbsms_url"=>@$fetch_app_info_main['wbsms_url']);
  $arr_fonts_sort=ARRAY("popularity"=>"Popularity","alpha"=>"Alphabetical","date"=>"Date","trending"=>"Trending");
    $arr_border_styles=ARRAY("Solid"=>"Solid","dashed"=>"Dashed","dotted"=>"Dotted","groove"=>"Groove","inset"=>"Inset","outset"=>"Outset","none"=>"None");
//////////////////buttons
$sql_buttons="select * from buttons";
$res_buttons=mysqli_query($link,$sql_buttons);
$arr_buttons;
while($row_buttons=mysqli_fetch_assoc($res_buttons)){
    $arr_temp['is_empty']=$row_buttons['is_empty'];
    $arr_temp['title']=$row_buttons['title'];
 $arr_buttons[$row_buttons['name']]=$arr_temp;   
}

?>
 <script>

var page_id="<?php echo @$_GET['id']?>";
var page_key="<?php echo $page_key;?>"
</script>
<script>
var latitude='<?php echo @$latitude;?>';
var longitude='<?php echo @$longitude;?>';
</script>


 <link rel="stylesheet" type="text/css" href="colorpicker/css/colorpicker.css" />
<style>
.button_template{
    border: 0px solid #ccc;
    margin-top: 10px;
}
.button_template img
{
 width: 200px;
 height: 60px;   
}
.widget-head{
    cursor: move;
}
</style>



<!-- <script src="js/jquery-1.8.js"></script> -->
<script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
    
<script src="js/jquery-ui-1.10.0.custom.js"></script>

     <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
 <link rel="stylesheet" type="text/css" href="css/dhtmlxcalendar.css"></link>
 <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyCc9MOpE2wArUTUcA67RHFfpI-BfIHrDCs&sensor=false"></script>
    <link rel="stylesheet" type="text/css" href="css/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
    <link rel="stylesheet" type="text/css" href="css/coupon.css"></link>
        <script src="js/dhtmlxcalendar.js"></script>
     <script src="js/nicEdit-latest.js" type="text/javascript"></script>
     <link href="css/pagination.css" rel="stylesheet" type="text/css" />
<link href="css/B_black.css" rel="stylesheet" type="text/css" />
  

    <script src="js/jquery.ui.touch-punch.min.js"></script>
      <script>
      var fonts_obj="";
      var pages_obj="";
      var campaigns_obj = "";
      var buttons_obj="";
      var buttons_types_obj="";
      var arr_align_obj="";
      var wbsms_obj="";
      var force_email_obj="";
      var simple_button_options_obj="";
      var fonts_sort_obj="";
      $(document).ready(function(){

     $("#column2 .widget_hover").show();
      fonts_obj=$.parseJSON('<?php echo $fonts_json?>');
   //   console.log('<?php echo $arr_pages_json;?>');  
     pages_obj=$.parseJSON('<?php echo @$arr_pages_json;?>');
     campaigns_obj=$.parseJSON('<?php echo @$arr_campaigns_json;?>');   
      
      buttons_obj=$.parseJSON('<?php echo json_encode($arr_buttons)?>');     
      buttons_types_obj=$.parseJSON('<?php echo json_encode($arr_button_type)?>');     
      arr_align_obj=$.parseJSON('<?php echo json_encode($arr_align)?>');     
      wbsms_obj=$.parseJSON('<?php echo json_encode($arr_wbsms_settings)?>');     
      force_email_obj=$.parseJSON('<?php echo json_encode($arr_force_email)?>');     
      simple_button_options_obj=$.parseJSON('<?php echo json_encode($arr_simple_button_options)?>');     
      fonts_sort_obj=$.parseJSON('<?php echo json_encode($arr_fonts_sort)?>');     
})
  </script>
     <script type="text/javascript" src="colorpicker/js/colorpicker.js"></script>
     <script src="js/script.js?ver=<?php echo $fetch_app_info_main['version'] ?>"></script>
          <script>
                    var active_content_widget_id="";
                    function larger_view(id){
                        active_content_widget_id=id;
                           var page_content=$("#content_"+id+" textarea[name=page_content]").prev("div").children(".nicEdit-main").html();
             //  page_content=page_content.replace(/"/g,'\'');
            //   var pat=/[^a-z0-9#$+-.,'"&%*=:(){}\]\[\/@<>\? ]/gi;
              // page_content=page_content.replace(pat,"");
               $("#editor").prev("div").children(".nicEdit-main").html(page_content);
                   var winH = $(window).height();
    var winW = $(window).width();
  var  vmid=(winH-$("#editor_wrapper").outerHeight())/2;
           vmid=$(window).scrollTop()+60;
 var hmid=(winW-$("#editor_wrapper").outerWidth())/2;
 //console.log(vmid+"----"+hmid);
  $("#editor_overlayer").css({width: $(document).width(), height: $(document).height(),display: 'block'});    
/// $("#editor_overlayer").show();
$("#editor_wrapper").css({top: vmid , left: hmid,visibility: 'visible','z-index' : 99999});
             //  console.log(page_content);
                    }
                    function close_editor(obj){
                         $("#editor_overlayer").css({'display' : 'none'});
    $(obj).parent("div").css({visibility: 'hidden'});
    var page_content=$("#editor").prev("div").children(".nicEdit-main").html();
               page_content=page_content.replace(/"/g,'\'');
             //  var pat=/[^a-z0-9#$+-.,'"&%*=:(){}\]\[\/@<>\? ]/gi;
             //  page_content=page_content.replace(pat,"");
    var page_content=$("#content_"+active_content_widget_id+" textarea[name=page_content]").prev("div").children(".nicEdit-main").html(page_content);
}
                    </script>
<script>
function bigger_view(id){
    
    $("#content_widget_div_"+id).css({border: '2px solid #ccc',width: '904px',height: '600px' ,position: 'absolute' ,left: '-600px',top: '-100px','background-color': 'white'});
    $("#content_widget_div_"+id+" .nicEdit-panelContain").css({width: '900px'});
    //$("#content_widget_div_"+id+":nth-child(2)").css({width: '900px'});
    $("#content_widget_div_"+id+"  .nicEdit-main").parent("div").css({width: '900px'});
    $("#content_widget_div_"+id+"  .nicEdit-main").css({width: '900px'});
}
</script>



 <style>
.check_widget{
    vertical-align: top;
    margin-top: 14px;
    cursor: pointer;
    float: left;
    margin-left: 15px;
    width: 18px;
    display: none;
 }
 .delete_widget{
    margin-right: 6px;
    float: right;
    cursor: pointer;
    margin-top: 5px;
    width: 35px;
 }
 .widget_hover{
    border: 0px solid red;
    width: 75px;
    height: 40px;
    margin: -7px;
    position: absolute;
    display: none;
    opacity: 0.7;
    float: right;
    right: 60px;
 }
 .placeholder{
     border: 2px dashed #999;
 }
 .google_map_wrapper{
     position: absolute; margin: -220px -565px; display: none; z-index: 1;
 }
 .google_map_div{
     border: 5px solid #ccc; width: 550px; height: 400px; background-color: #e2e2e2;
 }
 .close_map{
     cursor: pointer; margin: -437px 0px 0px 540px; z-index: 2; position: absolute;
 }
 .map_address_textarea{
     width: 218px; height: 45px;
 }
.larger_view{
color: #333;
margin-left: 100px;
}
.larger_view:hover{
color: #999;
}
 </style>
 <script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script>
function create_new(){
window.location.href="create_pages.php";    
}


function slideW(){
    $("#second_slide").slideDown();
    $("#first_slide").slideUp();
}

</script>
    
    <link rel="stylesheet" type="text/css" href="css/coupon.css"></link>
    
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								Create / Edit Page
								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location='view_pages.php'" />
							</h4>
							<p class="category">Create your awesome page here.</p>
						</div>
						<div class="content table-responsive">
							
                            
                            <div id="bars_container">
                            
                                 <div id="create_campaign" class="main_campaign_slider" style="display: block;">
                            <!--Create Campaign--->
                            <div id="columns">  
                            <div id="first_slide">
                                <fieldset style=" margin: -10px 10px 10px 10px; border-radius: 7px; padding-top: 10px;" id="slider_settings_0">
                                             <div class="form-group">
                                                <label>
                                                Page Title</label>
                                                <input type="text" name="page_title"  class="text_feild_cam" value="<?php echo @$obj['settings_0']['page_title']?>" /><span></span>
                                                <img alt="" id="uploaded_thumb_0" <?php if( @$obj['settings_0']['header_type'] == "image") echo 'src="uploaded_images/thumbs/'.@$obj['settings_0']['header_image'].'?php"'; else echo 'src=""';?> />
                                              </div>
                                              <div class="form-group">
                                                <label>
                                              Select background</label>
                                                <input  name="slider_settings_0" onclick="show_hide_header_img_video('slider_settings_0',this)" type="radio" value="header_video" <?php if(@$obj['settings_0']['header_type'] == "color") echo 'checked="checked"';?>/> Color
                                              
                                                <input  name="slider_settings_0" onclick="show_hide_header_img_video('slider_settings_0',this)" type="radio" value="header_image"  <?php if(@$obj['settings_0']['header_type'] == "image") echo 'checked="checked"';?>/> Image
                                              </div>
                                              <div class="form-group" id="header_video" <?php if( @$obj['settings_0']['header_type'] != "color") echo 'style="display: none;"';?>>
                                                <label>
                                                Main BG Color </label>
                                                <input type="color" name="page_bg_color" id="page_bg_color" class="color_picker text_feild_cam" value="<?php echo @$obj['settings_0']['header_color']?>" />
                                              </div>
                                              <div class="form-group" id="header_image" <?php if( @$obj['settings_0']['header_type'] != "image") echo 'style="display: none;"';?>>
                                                <label>
                                                Main BG Image</label>
                                                                <form  method="POST" enctype="multipart/form-data">
                            
                            <input id="slider_image_0" type="file" size="7" name="fileToUpload" style="margin-top: 5px;">
                            <button class="button" onclick="return ajaxFileUpload('slider_image_0','loading_0','uploaded_thumb_0','image');" style="margin-top: 5px;">Upload</button>
                                         <br/>       <img id="loading_0" src="load9.gif" style="display: none;">
                                                
                                            </form>
                                                
                                              </div>
                                                   <div class="form-group">
                                               <label>
                                              Enter Address:</label>
                                                <textarea name="address" class="text_feild_cam map_address form-control"  id="map_address_0" onfocus="load_map('0')" ><?php echo @$obj['settings_0']['address']?></textarea>
                                                <div id="wrapper_0" style="position: absolute; margin-left: 0px; display: none; z-index: 1; padding-right: 20px; border: 0px solid red; width: 570px; height: 420px;">
                                                    <div id="map_div_0" style="border: 5px solid #ccc; width: 550px; height: 400px; background-color: #e2e2e2;  "></div>
                                                    <img src="images/close_buttonc.png" style=" cursor: pointer; z-index: 2;  border: 0px solid red;" id="close_map_0" class="close_map" />
                                                </div>
                                      <div id="map_0" style="display: none;">       <input type="hidden" name="lat" value="<?php echo @$obj['settings_0']['lat']?>"/>
                                             <input  type="hidden" name="lon" value="<?php echo @$obj['settings_0']['lon']?>"/></div>   
                                                
                                              </div>
                                              <div class="form-group">
                                              <label>Border thickness: <small>Border width, suggested value(0-8)</small> </label>
                                                <input type="text" name="border_thickness"  class="text_feild_cam" value="<?php echo @$obj['settings_0']['border_thickness']?>" />
                                                
                                              </div> 
                                               <div class="form-group">
                                               <label>Border style: <small>Border design, suggested value(none, solid)</small> </label>
                                                <select name="border_style"  class="text_feild_cam" style="padding: 2px;">
                                                <?php foreach($arr_border_styles as $key =>$val) {
                                                    if(@$obj['settings_0']['border_style'] == $key)
                                                    echo "<option value='$key' selected='selected'>$val</option>";
                                                    else
                                                    echo "<option value='$key'>$val</option>";
                                                }?>
                                                </select>
                                                
                                              </div>
                                              <div class="form-group">
                                                 <label>Border Color: </label>
                                                <input name="border_color" type="color"  class="text_feild_cam color_picker" value="<?php echo @$obj['settings_0']['border_color']?>" >
                                                
                                              </div>
                                                        <div class="form-group">
                                               <label>
                                             Custom JS code:  <small>(Any javascript code here)</small></label>
                            <textarea name="js_code"  class="text_feild_cam form-control" ><?php echo @$obj['settings_0']['js_code']?></textarea>
                            </div>
                                    <div class="form-group text-right m-b-0">
                                        <button class="btn btn-info" type="button" onclick="slideW()"> Next >></button>
                                        <button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>
                                    </div>
                             
                             <?php //print_r(@$obj)?>  
                             </fieldset>
                            </div>
                            <div id="second_slide" style="display:none;" class="row">
                                <div id="Container_of_widgets" class="col-md-4">
                                    <div id="container_info">Available Widgets</div>
                                    <div id="widget_item_container">
                                     <ul id="column1" class="column">
                            
                                      <li class="widget business_map" id="fonts"> 
                                          <div class="widget_hover"> 
                            <img src="images/enable.png" class="check_widget" onclick="check_widget(this)">
                            <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                          </div>
                                        <div class="widget-head">
                                        <h3>
                                        Headline
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                            </li> 
                            
                            
                                        <li class="widget" id="content">
                                         <div class="widget_hover">
                                          <img src="images/enable.png" class="check_widget" onclick="check_widget(this)">
                             <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)"></div>
                            
                                        <div class="widget-head">
                                        <h3>
                            Page content
                                        </h3><span class="drop_arrow"  onclick="make_doggle(this)"></span>
                                        </div>
                                        </li>
                              
                                           <li class="widget drag_social_icon" id="icons"> 
                                            <div class="widget_hover">
                                                            <img src="images/enable.png" class="check_widget" onclick="check_widget(this)">
                            
                                            <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                            </div>
                                        <div class="widget-head">
                                        <h3>
                                    Social Share
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                               
                                        </li>
                             
                                    <li class="widget business_map" id="scratch">
                               <div class="widget_hover">
                                             <img src="images/enable.png" class="check_widget" onclick="check_widget(this)"> 
                            
                                          <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)"> 
                                          </div>
                                        <div class="widget-head">
                                        <h3>
                               Scratch'N Share
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                   
                                        </li> 
                                                   <li class="widget business_map" id="map">
                               <div class="widget_hover">
                                             <img src="images/enable.png" class="check_widget" onclick="check_widget(this)"> 
                            
                                          <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)"> 
                                          </div>
                                        <div class="widget-head">
                                        <h3>
                                    Map-Get Directions
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                   
                                        </li>
                                        
                                          <li class="widget" id="header">
                                                     <div class="widget_hover">
                                                     <img src="images/enable.png" class="check_widget" onclick="check_widget(this)">
                                                     <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                                     
                                                     </div>
                                        <div class="widget-head">
                                        <h3>
                            Image/Video
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                            
                                        </li>
                                        
                                        <li class="widget" id="redeem" style="display: none;"> 
                                        <div class="widget_hover">
                                                     <img src="images/enable.png" class="check_widget" onclick="check_widget(this)"> 
                            
                                         <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)"> 
                                         </div>
                                        <div class="widget-head">
                                        <h3>
                            Redeem Button
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                            
                                       
                                        </li>
                                                <li class="widget business_map" id="scarcity" style="display: none;"> 
                                          <div class="widget_hover">
                                        <img src="images/enable.png" class="check_widget" onclick="check_widget(this)">
                                        <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                        </div>
                                        <div class="widget-head">
                                        <h3>
                                        Scarcity Widget 
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        </li>
                                        
                            <li class="widget drag_button" id="button"> 
                                                 <div class="widget_hover">
                                                                      <img src="images/enable.png" class="check_widget" onclick="check_widget(this)">
                            
                                                 <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                                 </div>
                                        <div class="widget-head">
                                        <h3>
                                       Simple Button
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        </li>
                                      
                            
                                    <li class="widget drag_c_to_call" id="call"> 
                                     <div class="widget_hover">
                                              <img src="images/enable.png" class="check_widget" onclick="check_widget(this)">
                            
                                     <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                     </div>
                                        <div class="widget-head">
                                        <h3>
                                    Call Button
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                
                                        </li>
                            
                             <li class="widget drag_content" id="timer">
                              <div class="widget_hover">
                                <img src="images/enable.png" class="check_widget" onclick="check_widget(this)">
                            
                              <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                              </div>
                                        <div class="widget-head">
                                        <h3>
                                        Count Down Timer 
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                    
                                        </li>
                            
                                    <li class="widget business_map" id="cart">
                                     <div class="widget_hover">
                                     <img src="images/enable.png" class="check_widget" onclick="check_widget(this)"> 
                            
                                     <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)"> 
                                     </div>
                                        <div class="widget-head">
                                        <h3>
                                        Add To Cart
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                            
                                        </li>
                            
                                             <li class="widget business_map" id="loyalty" style="display: none;"> 
                                          <div class="widget_hover"> 
                            <img src="images/enable.png" class="check_widget" onclick="check_widget(this)">
                            <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                          </div>
                                        <div class="widget-head">
                                        <h3>Loyalty</h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                            </li> 
                             
                                         <li class="widget business_map" id="facebook">
                                          <div class="widget_hover">  
                                                        <img src="images/enable.png" class="check_widget" onclick="check_widget(this)">
                            
                                          <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                          </div>
                                        <div class="widget-head">
                                        <h3>
                                        Facebook Comments
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        </li>
                             
                                         <li class="widget business_map" id="twitter"> 
                                          <div class="widget_hover"> 
                                                        <img src="images/enable.png" class="check_widget" onclick="check_widget(this)">
                                          <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                          </div>
                                        <div class="widget-head">
                                        <h3>
                                        Twitter feed
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        </li> 
                                        <!------->
                                        <li class="widget" id="webform">
                                                     <div class="widget_hover">
                                                     <img src="images/enable.png" class="check_widget" onclick="check_widget(this)">
                                                     <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                                     
                                                     </div>
                                        <div class="widget-head">
                                        <h3>
                            Webform
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                            
                                        </li>
                                        <!------->      
                               
                                    </ul>
                                    </div>   
                                    </div>
                                     <div id="Container_of_preview" class="col-md-8">
                                        <div id="container_info">Setting Up Widgets</div>
                                        <div id="widget_item_container">
                                        <ul id="column2" class="column">
                                                 <?php
                                  $header=1;
                                 $content=1;
                                 $redeem=1;
                                 $button=1;
                                 $icons=1;
                                 $call=1;
                                 $timer=1;
                                 $cart=1;
                                 $scarcity=1;
                                 $facebook=1;
                                 $map=1;
                                 $twitter=1;
                                 $webform=1;
                                 $scratch=1;
                                 $fonts=1;
                                 $loyalty=1;
                                 $arr_color_picker[]="#slider_settings_0";
                                 $arr_editor=array();
                                 $arr_calendar=array();
                                 $arr_calendar_only_date=array();
                                 if(isset($obj))
                                {       
                                   foreach($obj as $key => $val)
                            {
                               
                                $li=substr($key,0,strpos($key,"_"));
                              
                                switch($li){
                                    
                                    case "header":{
                                       
                                    /*        if(isset($obj[$key]['header_type']))
                                            {
                                                if($obj[$key]['header_type']=="image")
                                                {
                                                $image_checked="checked='checked'";
                                               $video_checked="";
                                               $img_disp="";
                                               $video_disp='style="display: none;"';
                                               if(isset($obj[$key]['header_image_url']))
                                               {
                                       
                                               } 
                                               else  if(isset($obj[$key]['header_image_name']))
                                          {
                                            $img_src=' src="uploaded_images/thumbs/'.$obj[$key]['header_image_name']."?".time().'"';   
                                          }
                                                }
                                                else if($obj[$key]['header_type']=="video")
                                                {
                                                 $video_checked="checked='checked'";
                                                $image_checked="";
                                                $video_disp="";
                                               $img_disp='style="display: none;"';   
                                                }
                                                if(isset($obj[$key]['header_video_url']))
                                                {
                                                    
                                                }
                                                else if(isset($obj[$key]['header_video_name']))
                                                {
                                                     $img_src=' src="images/video.png"';
                                                }
                                            }
                                        */  
                                           
                                       
                                      ?>
                                                 <li class="widget" id="header_<?php echo $header?>">
                                                 <div class="widget_hover">
                                                   <img alt="" src="<?php if(isset($obj[$key]['check']))echo $obj[$key]['check']; else echo 'images/enable.png'?>" class="check_widget" onclick="check_widget(this)">
                                                  <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)"></div>
                                                 <div class="widget-head">
                                        <h3>
                            Image/Video
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_header_<?php echo $header?>" class="slider_area" align="center">
                                            <table width="100%" border="0">
                                              <tr>
                                                <td align="center">
                                                <?php ///print_r($obj[$key]);?>
                                            BG Image :
                                                <input  name="slider_header_<?php echo $header?>" onclick="show_hide_header_img_video('slider_header_<?php echo $header?>',this)" type="radio" value="header_image" <?php  if($obj[$key]['header_type']=="image") echo 'checked="checked"';?> />
                                            BG Video :
                                                <input  name="slider_header_<?php echo $header?>" onclick="show_hide_header_img_video('slider_header_<?php echo $header?>',this)" type="radio" value="header_video" <?php if($obj[$key]['header_type']=="video") echo 'checked="checked"';?>/>
                                                </td>
                                              </tr>
                                              <tr id="header_image" <?php  if($obj[$key]['header_type']!="image") echo 'style="display: none;"';?>>
                                                <td>
                                                <table ><tr><td>Upload Image:<input type="radio" name="image_choice_<?php echo $header?>" value="image_upload" onclick="show_hide_header_img_video('slider_header_<?php echo $header?>',this)" <?php if(isset($obj[$key]['header_image_name'])) echo 'checked="checked"';?> /> Image URL: <input type="radio" name="image_choice_<?php echo $header?>" value="image_url" onclick="show_hide_header_img_video('slider_header_<?php echo $header?>',this)" <?php if(isset($obj[$key]['header_image_url'])) echo 'checked="checked"';?> /></td></tr>
                                                <?php 
                                                
                                                {
                                                    
                                                }
                                                ?>
                            <tr id="image_upload" <?php if(!isset($obj[$key]['header_image_name'])) echo'style="display: none;"'?>><td>
                                            Upload Image :
                                                <form  method="POST" enctype="multipart/form-data">
                            
                            <input id="slider_image_<?php echo $header?>" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;">
                            <button class="button" onclick="return ajaxFileUpload('slider_image_<?php echo $header?>','loading_<?php echo $header?>','uploaded_thumb_<?php echo $header?>','image');" style="margin-top: 5px;">Upload</button>
                                                <img id="loading_<?php echo $header?>" src="load9.gif" style="display: none;">
                                                <img alt="" id="uploaded_thumb_<?php echo $header?>"  <?php if(isset($obj[$key]['header_image_name'])) echo ' src="uploaded_images/thumbs/'.$obj[$key]['header_image_name']."?".time().'"';?>/>
                                    </form>
                                            </td></tr>
                                            <tr id="image_url"   <?php if(!isset($obj[$key]['header_image_url'])) echo'style="display: none;"'?>>
                                            <td>Enter url: <input type="text" name="image_url" value="<?php echo $obj[$key]['header_image_url']?>" /><td></td></tr>
                                            </table>
                                                </td>
                                              </tr>
                                              <tr id="header_video" <?php if($obj[$key]['header_type']!="video") echo 'style="display: none;"';?>>
                                                <td>
                                                <table><tr><td>Upload Video:<input type="radio" name="video_choice_<?php echo $header?>" value="video_upload" onclick="show_hide_header_img_video('slider_header_<?php echo $header?>',this)" <?php if(isset($obj[$key]['header_video_name'])) echo 'checked="checked"';?>/> Video URL: <input type="radio" name="video_choice_<?php echo $header?>" value="video_url" onclick="show_hide_header_img_video('slider_header_<?php echo $header?>',this)" 
                             <?php if(isset($obj[$key]['header_video_url'])) echo 'checked="checked"';?> /></td></tr>
                                                <tr id="video_upload"  <?php if(!isset($obj[$key]['header_video_name'])) echo'style="display: none;"'?>><td>
                                             Upload Video
                                                               <form  method="POST" enctype="multipart/form-data">
                            
                            <input id="slider_video_<?php echo $header?>" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;">
                            <button class="button" onclick="return ajaxFileUpload('slider_video_<?php echo $header?>','vloading_<?php echo $header?>','uploaded_video_<?php echo $header?>','video');" style="margin-top: 5px;">Upload</button>
                                                <img id="vloading_<?php echo $header?>" src="load9.gif" style="display: none;">
                                                <input type="hidden" name="hidden_video_name" value="<?php echo $obj[$key]['header_video_name']?>" />
                                                <img alt="" width="150" height="150" id="uploaded_video_<?php echo $header?>" <?php if(isset($obj[$key]['header_video_name'])) echo 'src="images/video.png"'; else echo 'src=""';?>/>
                                    </form>
                                        </td></tr>
                                         <tr id="video_url"   <?php if(!isset($obj[$key]['header_video_url'])) echo'style="display: none;"'?>>
                                         <td>Enter url: <input type="text" name="video_url" value="<?php echo $obj[$key]['header_video_url']?>" class="text_feild_cam" /></td></tr>
                                             <tr> <td>Enter Width: <input type="text" name="video_width" value="<?php echo $obj[$key]['video_width']?>" class="text_feild_cam" style="width: 100px; height: 20px" /></td></tr>
                                         <tr> <td>Enter Height: <input type="text" name="video_height" value="<?php echo $obj[$key]['video_height']?>" class="text_feild_cam" style="width: 100px; height: 20px"/>
                                         <div class="hint">Give height in pixels(suggested 250).Width is also in pixels(leave empty for 100%)</div>
                                         </td></tr>
                                         <tr><td>Auto Play: <input type="checkbox" name="autoplay" <?php if($obj[$key]['autoplay'] == "true") echo "checked='checked'";?>/>  
                                         Loop video: <input type="checkbox" name="loop"  <?php if($obj[$key]['loop'] == "true") echo "checked='checked'";?>/></td></tr>
                                     
                                        </table></td></tr>
                             <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border']== "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG: <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                            </table>
                                        </div>
                                        </li> 
                             <?php         
                                   $header++;
                                    };break;
                                    case "content":{
                                        $arr_editor[]="page_content_$content";
                                        ?>
                                            <li class="widget" id="content_<?php echo $content?>">
                                            <div class="widget_hover">
                                                <img alt="" src="<?php if($obj[$key]['check']!="")echo $obj[$key]['check']; else echo 'images/enable.png'?>" class="check_widget" onclick="check_widget(this)" >
                                             <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                         </div>
                                        <div class="widget-head">
                                        <h3>
                            Page content
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_content_<?php echo $content?>" class="slider_area">
                                        <table width="100%" border="0" align="center">
                                             <tr>
                                                <td>   Content <!-- <a href="javascript:void(0);" onclick="larger_view('<?php echo $content?>')" class="larger_view">Larger View</a> -->
                            <textarea id="page_content_<?php echo $content?>" class="text_area_cam" name="page_content"><?php echo str_replace("http//","http://",$obj[$key]['page_content'])?></textarea>
                                                </td>
                                              </tr>
                            <tr><td>No Padding: <span id="checkbox_pos_soc"><input type="checkbox" name="no_padding"  <?php if($obj[$key]['no_padding'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                          </table>
                                        </div>
                                        </li> 
                                        <?php
                                       $content++; 
                                    };break;
                                    case "redeem":{
                                             $arr_color_picker[]="#redeem_$redeem";    
                            /// echo "Email case=".$obj[$key]['force_optin']['force_optin_email_case'];        
                                        ?>
                                  
                                                    <li class="widget" id="redeem_<?php echo $redeem?>">  
                                                    <div class="widget_hover">
                            <img alt="" src="<?php if($obj[$key]['check']!="")echo $obj[$key]['check']; else echo 'images/enable.png'?>" class="check_widget" onclick="check_widget(this)" />
                            <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                                     </div>
                                        <div class="widget-head">
                                        <h3>
                            Redeem Button
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_redeem_<?php echo $redeem?>" class="slider_area">
                                        <table width="100%" border="0" align="center">
                                              <tr>
                                                <td>
                                                <label for="redeem_label">Button Label
                                                <input type="text" name="redeem_label" id="redeem_label" class="text_feild_cam" value="<?php echo $obj[$key]['redeem_label']?>" />
                                                </label>
                                                </td>
                                              </tr>
                                              <tr> <td>Font Color <input type="color" name="redeem_color" id="redeem_color" class="text_feild_cam color_picker" value="<?php echo $obj[$key]['redeem_color']?>"  /> </td> </tr>
                                              <tr>
                                                <td>
                                              Font Size: <small>(PX is auto included)</small>
                                              <input type="text" name="font_size" class="text_feild_cam"  value="<?php if(isset($obj[$key]['font_size'])) echo $obj[$key]['font_size']?>" />
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                <label for="redeem_bg_color">Button BG Color
                                                <input type="color" name="redeem_bg_color" id="redeem_bg_color_<?php echo $redeem?>" class="color_picker text_feild_cam"  value="<?php echo $obj[$key]['redeem_bg_color']?>" />
                                                </label>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                <label for="redeem_hover_color">Button HOVER Color
                                                <input type="color" name="redeem_hover_color" id="redeem_hover_color_<?php echo $redeem?>" class="color_picker text_feild_cam" value="<?php echo $obj[$key]['redeem_hover_color']?>" />
                                                </label>
                                                </td>
                                              </tr>
                                              <tr><td>Refresh Period<input type="text" name="refresh_period" class="text_feild_cam" value="<?php echo $obj[$key]['refresh_period']?>"/></tr>
                                              <tr>
                                                <td>
                                                  Select Page:
                                                <select name="redeem_page_url" class="text_feild_cam_select">
                                                <option value="">Select Any</option>
                                                <?php
                                                    foreach($arr_pages as $key_p => $val_p){
                                                        if($obj[$key]['redeem_page_url'] == $key_p)
                                                        {
                                                            $sel="selected='selected'";
                                                        }
                                                        else
                                                        $sel="";
                                                  echo "<option value='$key_p' $sel >$val_p</option>";      
                                                    }
                                                ?>
                                                </select>
                                                </td>
                                              </tr>
                            
                            
                            <tr><td>Thank You page text<textarea class="text_area_cam" name="redeem_text"><?php echo $obj[$key]['redeem_text']?></textarea><span class="hint">You may use %refresh% to display after how many days users can get another coupon and %UPC% to display the barcode of coupon number.</span></td></tr>
                            <tr><td>Redeem Prompt<textarea class="text_area_cam" name="redeem_prompt"><?php echo $obj[$key]['redeem_prompt']?></textarea></td></tr>
                            <tr><td>Redeem Once<input type="checkbox" value="yes" name="redeem_once" <?php if($obj[$key]['redeem_once'] == "no") echo ''; else echo 'checked="checked"';  ?>/></td></tr>
                                                      <tr>
                                                <td align="center">
                                            Upload Image :
                                                <input  name="slider_redeem_<?php echo $redeem?>" onclick="show_hide_header_img_video('slider_redeem_<?php echo $redeem?>',this)" type="radio" value="redeem_image" <?php if($obj[$key]['redeem_img_type'] == "image") echo 'checked="checked"';?>/>
                                            Image URL :
                                 <input  name="slider_redeem_<?php echo $redeem?>" onclick="show_hide_header_img_video('slider_redeem_<?php echo $redeem?>',this)" type="radio" value="redeem_img_url" <?php if($obj[$key]['redeem_img_type'] == "url") echo 'checked="checked"';?>/>
                                                </td>
                                              </tr>
                                  
                                              <tr id="redeem_img_url" <?php if($obj[$key]['redeem_img_type'] !="url" )echo 'style="display: none;"';?>>
                                                <td>
                                                Image URL
                                                <input type="text" name="redeem_img_url" class="text_feild_cam"  value="<?php if(isset($obj[$key]['redeem_img_url'])) echo $obj[$key]['redeem_img_url']?>" />
                                                                    </td>
                                              </tr>
                                              <tr id="redeem_image" <?php if($obj[$key]['redeem_img_type'] != "image") echo 'style="display: none;"';?>><td>           Upload Image :
                                                <form  method="POST" enctype="multipart/form-data">
                            
                            <input id="redeem_image_<?php echo $redeem?>" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;">
                            <button class="button" onclick="return ajaxFileUpload('redeem_image_<?php echo $redeem?>','redeem_loading_<?php echo $redeem?>','redeem_thumb_<?php echo $redeem?>','image');" style="margin-top: 5px;">Upload</button>
                                                <img id="redeem_loading_<?php echo $redeem?>" src="load9.gif" style="display: none;">
                                                <img alt="" id="redeem_thumb_<?php echo $redeem?>" <?php if(isset($obj[$key]['redeem_img_name'])) echo 'src="uploaded_images/thumbs/'.$obj[$key]['redeem_img_name'].'?'.@$time.'"'; else echo 'src=""';?>/>
                                    </form></td></tr>
                                    <tr><td>Redeem Button </td></tr>
                                               <tr>
                                                <td align="center">
                            <select name="choose_button_type_<?php echo $redeem?>" onchange="show_hide_option('slider_redeem_<?php echo $redeem?>','hide_options',this)" class="text_feild_cam_select"><?php 
                            foreach($arr_button_type as $key_c =>$val_c){
                                if($obj[$key]['redeem_button_type'] == $key_c)
                                $sel="selected='selected'";
                                else $sel="";
                            echo "<option value='$key_c' $sel >$val_c</option>";    
                            }
                            ?></select>
                                                </td>
                                              </tr>
                            
                                              <tr id="button_template" class="hide_options" <?php if($obj[$key]['redeem_button_type'] != "button_template") echo 'style="display: none;"';?>>
                                                <td>
                                               Select button template
                                              <select name="redeem_button_template" onchange="show_button_template(this,'button_redeem_<?php echo $redeem?>')" class="text_feild_cam_select">
                                              <option value="">---Select Any---</option>
                                            <?php
                                               foreach($arr_buttons as $key_b => $val_b){
                                                   if($obj[$key]['redeem_button_type'] == "button_template")
                                                  { if($obj[$key]['redeem_button_template'] == $key_b)
                                                   {
                                                       $sel='selected="selected"';
                                                      $is_empty=$val_b['is_empty'];
                                                     // $test= $key_b."---".$obj[$key]['redeem_button_template'];                  
                                                   }
                                                   else 
                                                   {
                                                   $sel="";
                                                   }
                                               //  $test="test".$obj[$key]['redeem_button_template'];
                                                  }
                                                   echo '<option value="'.$key_b."++"."$val_b[is_empty]".'" '.$sel.'>'.$val_b['title'].'</option>';
                                               }   
                                              ?>
                                              </select>
                                            
                            
                                               <div id="button_redeem_<?php echo $redeem;?>" class="button_template"><img alt="" <?php if(isset($obj[$key]['redeem_button_template'])) echo 'src="images/buttons/'.$obj[$key]['redeem_button_template'].'"'; else echo 'src=""';?>/>
                                              <input type="hidden" name="is_empty" value="<?php echo $is_empty?>"/>
                                              </div>
                                        </td>
                                              </tr>
                            <tr id="button_image" class="hide_options" <?php if($obj[$key]['redeem_button_type'] != "button_image") echo 'style="display: none;"';?>><td> 
                              Upload Image :
                              <form  method="POST" enctype="multipart/form-data">
                            <input id="redeem_button_image_<?php echo $redeem?>" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;">
                            <button class="button" onclick="return ajaxFileUpload('redeem_button_image_<?php echo $redeem?>','redeem_button_loading_<?php echo $redeem?>','redeem_button_thumb_<?php echo $redeem?>','image');" style="margin-top: 5px;">Upload</button>
                            <img id="redeem_button_loading_<?php echo $redeem?>" src="load9.gif" style="display: none;">
                            <img alt="" id="redeem_button_thumb_<?php echo $redeem?>" <?php if(isset($obj[$key]['redeem_button_image'])) echo 'src="uploaded_images/thumbs/'.$obj[$key]['redeem_button_image'].'?'.@$time.'"'; else echo 'src=""';?>/></form></td></tr>
                            <tr><td>Add close button for clerk: <span id="checkbox_pos_soc"><input type="checkbox" id="add_close_<?php echo $redeem?>" <?php if($obj[$key]['add_close'] == "true") echo "checked='checked'";?> /></span></td></tr>
                            <tr><td>Force Optin:<span id="checkbox_pos_soc"><input type="checkbox" id="force_optin_<?php echo $redeem?>" onclick="show_next_option(this);" <?php if($obj[$key]['force_optin_check'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                 <tr <?php if($obj[$key]['force_optin_check'] == "false") echo "style='display: none;'";?>><td> &nbsp;  &nbsp;  &nbsp;  &nbsp;  Email:  <input type="radio" name="redeem_email_<?php echo $redeem?>" value="redeem_email_<?php echo $redeem?>" onclick="show_hide_option('slider_redeem_<?php echo $redeem?>','hide_force_options',this)" <?php if($obj[$key]['force_optin_case'] == "email") echo "checked='checked'";?> /> &nbsp;  &nbsp;  &nbsp;  &nbsp;
                                 SMS: <input type="radio" name="redeem_email_<?php echo $redeem?>" value="redeem_sms_<?php echo $redeem?>" onclick="show_hide_option('slider_redeem_<?php echo $redeem?>','hide_force_options',this)"  <?php if($obj[$key]['force_optin_case'] == "sms") echo "checked='checked'";?>/> <table>
                                 <tr id="redeem_email_<?php echo $redeem?>" class="hide_force_options" <?php if($obj[$key]['force_optin_case'] != "email") echo "style='display: none;'";?>><td><?php echo $obj[$key]['force_optin']['force_optin_email_case']?><select name="redeem_force_email" onchange="show_const_contact(this,'slider_redeem_<?php echo $redeem?>')" class="text_feild_cam_select">
                                 <?php foreach($arr_force_email as $key_c =>$val_c){
                                     if($obj[$key]['force_optin']['force_optin_email_case'] == $key_c)
                                     $sel="selected='selected'";
                                     else 
                                     $sel="";
                                     echo "<option value='$key_c' $sel >$val_c</option>";
                                 }?></select>
                                <textarea id="email_code" class="text_area_cam" <?php if($obj[$key]['force_optin']['force_optin_email_case'] == "const_contact") echo 'style="display: none;"'?> onblur="force_optin_form_dom(this,'slider_redeem_<?php echo $redeem?>')"> <?php echo $obj[$key]['force_optin']['form_action'];?><?php switch($obj[$key]['force_optin']['form_optin_email_case']){case"aweber": echo "Aweber code\n".$obj[$key]['form_action']; break; case"mailchimp": echo "Mailchimp code\n".$obj[$key]['form_action']; break; case"icontact": echo "Icontact code\n".$obj[$key]['form_action']; break; case"getresponse": echo "Get Response Code\n".$obj[$key]['form_action']; break; case"sendreach": echo "Sendreach Code\n".$obj[$key]['form_action']; }?></textarea>
                               <div id="cc_form_json" style="display: none;"><?php echo json_encode($obj[$key]['force_optin'])?></div>
                               <div id="cc_form_dom" style="display: none;"></div>
                                <div id="get_const_contact"  <?php if($obj[$key]['force_optin']['force_optin_email_case'] != "const_contact") echo "style='display: none;'";?>>
                                <table>
                                <tr><td>Email:<input type="text" name="email_const_contact" class="text_feild_cam" value="<?php echo $obj[$key]['force_optin']['email_const_contact']?>" /></td></tr>
                                <tr><td>password:<input type="text" name="pass_const_contact" class="text_feild_cam" value="<?php echo $obj[$key]['force_optin']['pass_const_contact']?>" /></td></tr>
                                <tr><td>API Key:<input type="text" name="apikey_const_contact" class="text_feild_cam" value="<?php echo $obj[$key]['force_optin']['apikey_const_contact']?>" /></td></tr>
                                <tr><td><input type="button" name="get_list_const_contact" value="Get List" onclick="get_list_const_contact(this,'slider_redeem_<?php echo $redeem?>')" /></td></tr>
                                <tr><td id="const_contact_list"><?php if(isset($obj[$key]['force_optin']['list_const_contact_id'])) echo '<select id="cc_list" class="text_feild_cam_select"><option value='.$obj[$key]['force_optin']['list_const_contact_id'].'>'.$obj[$key]['force_optin']['list_const_contact_name'].'</option></select>';?></td></tr>
                                </table>
                                </div> 
                                 </td></tr>
                                 <tr id="redeem_sms_<?php echo $redeem?>" class="hide_force_options" <?php if($obj[$key]['force_optin_case'] != "sms") echo "style='display: none;'";?>><td>
                                 
                                      <input type="button" value="Get Campaigns" onclick="redeem_get_campaigns(this,'slider_redeem_<?php echo  $redeem?>');" style="margin:5px 0px;"/>
                            <div id="redeem_user_campaigns"><?php if(isset($obj[$key]['force_optin']['user_campaign_id'])) echo "<select name='user_campeigns'  class='text_feild_cam_select'><option title='".$obj[$key]['force_optin']['user_campaign_phone']."' value='".$obj[$key]['force_optin']['user_campaign_id']."'>".$obj[$key]['force_optin']['user_campaign_name']."</option></select>";?></div>
                                 </td></tr></table></td></tr>
                              <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                 <tr><td align="right"><a href="javascript:void(0);" class="link" onclick="reset_redeems(this);" >Reset Redeems</a></td></tr>
                                          </table>
                                        </div>
                                        </li>
                                        <?php
                                        $redeem++;
                                    };break;  
                                    case "button":{
                                        $arr_color_picker[]="#button_$button";
                                        
                                        ?>
                                                                      <li class="widget drag_button" id="button_<?php echo $button?>"> 
                                                                       <div class="widget_hover">
                            <img alt="" src="<?php if($obj[$key]['check']!="")echo $obj[$key]['check']; else echo 'images/enable.png'?>" class="check_widget" onclick="check_widget(this)"> 
                             <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)"> 
                                   
                                       </div>
                                        <div class="widget-head">
                                        <h3>
                                       Simple Button
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_button_<?php echo $button?>" class="slider_area">
                                        <table width="100%" border="0" align="center">
                                             <tr>
                                                <td>
                                                Button BG Color
                                                <input type="color" name="simple_button_bg_color" id="simple_button_bg_color_<?php echo $button?>" class="color_picker text_feild_cam" value="<?php echo $obj[$key]['button_bg_color']?>"/>
                                                </td>
                                              </tr>
                                             <tr>
                                                <td>
                                                Button HOVER Color
                                                <input type="color" name="simple_button_hover_color" id="simple_button_hover_color_<?php echo $button?>" class="color_picker text_feild_cam" value="<?php echo $obj[$key]['button_hover_color']?>" />
                                                </td>
                                              </tr>
                              <tr> <td>Font Color <input type="color" name="font_color"  class="text_feild_cam color_picker" value="<?php echo $obj[$key]['font_color']?>"  /> </td> </tr>
                                              <tr><td>  Upload Image :
                                                <form  method="POST" enctype="multipart/form-data">
                            <input id="button_image_<?php echo $button?>" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;">
                            <button class="button" onclick="return ajaxFileUpload('button_image_<?php echo $button?>','button_loading_<?php echo $button?>','button_thumb_<?php echo $button?>','image');" style="margin-top: 5px;">Upload</button>
                                                <img id="button_loading_<?php echo $button?>" src="load9.gif" style="display: none;">
                                                <img alt="" id="button_thumb_<?php echo $button?>" <?php if(isset($obj[$key]['simple_button_image'])) echo 'src="uploaded_images/thumbs/'.$obj[$key]['simple_button_image'].'?'.@$time.'"'; else echo 'src=""';?>/>
                                    </form></td></tr>
                                              <tr><td><select name="simple_button_type" class="text_feild_cam_select">
                                 <?php foreach($arr_simple_button_options as $key_c =>$val_c){
                                     if($obj[$key]['simple_button_type'] == $key_c){
                                         $sel="selected='selected'";
                                     }
                                     else
                                     $sel="";
                                     echo "<option value='$key_c' $sel >$val_c</option>";
                                 }?></select></td></tr>
                                        <tr>
                                                <td>
                                               <select name="text_alignment" class="text_feild_cam_select">
                            <?php 
                            foreach($arr_align as $key_c => $val_c){
                                  if($obj[$key]['text_alignment'] == $key_c)
                                $sel='selected="selected"';
                                else
                                $sel="";
                            echo "<option value='$key_c' $sel>$val_c</option>";
                            }?>
                            </select>
                                                </td>
                                              </tr>
                            <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                            <tr>
                                                <td align="center">
                                                <div id="buttons"> 
                                              <?php
                                                  foreach($obj[$key]['buttons'] as $k_buttons=>$v_buttons)
                                                  {
                                                    //  print_r($v_buttons);
                                                    //  echo "<hr>";
                                                  $button_count=++$k_buttons; 
                                                $button_id=uniqid();   
                                               ?>
                                                 
                                                
                                                <table id="inner_table_<?php echo $button_id;?>"><tr><td  align="center">
                                                 <strong>Simple Button <?php echo $button_count?></strong>
                                                 <?php
                                                     if($button_count>1)
                                                 {
                                                 ?>
                                                 <img src="images/bin.png" style="margin:0px 0px -5px 50px" onclick="delete_button('inner_table_<?php echo $button_id?>')"/>
                                                 <?php
                                                 }
                                                 ?>
                                                 </td></tr>
                                                                    <tr>
                            <td>
                            Button Lable
                            <input id="simple_button_label_<?php echo $button_id?>" class="text_feild_cam" type="text"  name="simple_button_label" value="<?php echo $v_buttons['button_label']?>">
                            </td>
                            </tr>
                            <tr>
                                                <td>
                                              Button URL
                                                <input type="text" name="simple_button_url" id="simple_button_url" class="text_feild_cam"  value="<?php echo $v_buttons['button_url']?>" />
                                                </td>
                                              </tr></table>
                                               <?php  
                                                  }
                                              ?></div>
                                                 </td>
                                              </tr>
                                                <tr>
                                                <td valign="bottom" id="create_button_here_<?php echo $button?>" class="simple_buttons">
                                                </td>
                                              </tr>
                                           <tr>
                                                <td align="center">
                                                <input type="button" onclick="create_simple_button('button_<?php echo $button?>')" value="Create New Button" />
                                                </td>
                                              </tr>
                                          </table>
                                        </div>
                                        </li>
                                        <?php
                                        $button++;
                                    };break;
                                    case "icons":{
                                        ?>
                                 <li class="widget drag_social_icon" id="icons_<?php echo $icons?>">  
                             <div class="widget_hover">
                                   <img src="<?php if($obj[$key]['check']!="")echo $obj[$key]['check']; else echo 'images/enable.png'?>" class="check_widget" onclick="check_widget(this)">
                            
                                  <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                  </div>
                                        <div class="widget-head">
                                        <h3>
                                    Social Share
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_icons_<?php echo $icons?>" class="slider_area">
                                        <table width="100%" border="0">
                                          <tr>
                                            <td>FACEBOOK :<span id="checkbox_pos_soc"><input type="checkbox" name="facebook"  <?php if($obj[$key]['fb']== "true") echo "checked='checked'"?>onclick="fb_url('icons_<?php echo $icons?>',this)" /></span></td>
                                          </tr>
                                  <tr id="facebook" <?php if($obj[$key]['fb'] !="true") echo "style='display: none;'"?>><td >
                             <table width="=100%" border="0">
                              <tr><td>
                            Facebook App ID:
                             <input name="fb_app_id" type="text" value="<?php echo $obj[$key]['fb_app_id']?>" class="text_feild_cam" style="width: 212px;"></td></tr>
                             <tr><td>
                            Share Title:
                             <input name="title" type="text" value="<?php echo $obj[$key]['fb_title']?>" class="text_feild_cam" style="width: 212px;"></td></tr>
                              <tr><td>
                              Share Caption:
                              <input name="caption" type="text" value="<?php echo $obj[$key]['fb_caption']?>"  class="text_feild_cam" style="width: 212px;"></td></tr>
                                <tr><td>
                              Share Description:
                            <textarea class="text_area_cam" name="description" style="width: 212px;"><?php echo $obj[$key]['fb_desc']?></textarea></td></tr>
                            <?php
                            $fb_btn_array = array('fb_icon.png'=>'Button Small 1','small_btn2.png'=>'Button Small 2','small_btn3.png'=>'Button Small 3','medium_btn1.png'=>'Button Medium 1','medium_btn2.png'=>'Button Medium 2','medium_btn3.png'=>'Button Medium 3','horizontal_btn1.jpg'=>'Horizontal Button 1','horizontal_btn2.png'=>'Horizontal Button 2','horizontal_btn3.png'=>'Horizontal Button 3');
                            ?>
                            <tr id="fb_button_template"><td>
                            	Select button template 
                                <select name="fb_button_template" onchange="show_button_template(this,'button_fb_<?php echo $icons; ?>')" class="text_feild_cam_select">
                                <?php
                            		foreach ($fb_btn_array as $k=>$v)
                            		{
                            	?>
                                		<option value="<?php echo $k; ?>" <?php if($obj[$key]['fb_button_template'] == $k) echo 'selected="selected"'; ?>><?php echo $v; ?></option>
                                <?php
                            		}
                            	?>
                                </select>
                                <?php
                            	if($obj[$key]['fb_button_template'] !='')
                            	{
                            		$srcimage = $obj[$key]['fb_button_template'];
                            	}
                            	else
                            	{
                            		$srcimage='';
                            	}
                            	?>
                                
                                <div id="button_fb_<?php echo $icons; ?>" class="fb_button_template" style="text-align:center"><img style="width:auto; height:auto;" src="images/buttons/<?php echo $srcimage;?>"/> <input type="hidden" name="is_empty" value="<?php echo $is_empty?>"/></div></td></tr>
                             <tr><td >
                                            Upload Image :<input  name="slider_icons_<?php echo $icons?>" onclick="show_hide_header_img_video('slider_icons_<?php echo $icons?>',this)" type="radio" value="icons_image" <?php if($obj[$key]['icons_img_type'] == "image") echo 'checked="checked"';?>/>
                                            Image URL :
                                 <input  name="slider_icons_<?php echo $icons?>" onclick="show_hide_header_img_video('slider_icons_<?php echo $icons?>',this)" type="radio" value="icons_img_url" <?php if($obj[$key]['icons_img_type'] == "url") echo 'checked="checked"';?>/>
                                                </td>
                                              </tr>
                                  
                                              <tr id="icons_img_url" <?php if($obj[$key]['icons_img_type'] !="url" )echo 'style="display: none;"';?>>
                                                <td>
                                                Image URL
                                                <input type="text" name="icons_img_url" class="text_feild_cam"  value="<?php if(isset($obj[$key]['icons_img_url'])) echo $obj[$key]['icons_img_url']?>" style="width: 212px;"/>
                                                                    </td>
                                              </tr>
                                              <tr id="icons_image" <?php if($obj[$key]['icons_img_type'] != "image") echo 'style="display: none;"';?>><td>           Upload Image :
                                                <form  method="POST" enctype="multipart/form-data">
                            
                            <input id="icons_image_<?php echo $icons?>" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;">
                            <button class="button" onclick="return ajaxFileUpload('icons_image_<?php echo $icons?>','icons_loading_<?php echo $icons?>','icons_thumb_<?php echo $icons?>','image');" style="margin-top: 5px;">Upload</button>
                                                <img id="icons_loading_<?php echo $icons?>" src="load9.gif" style="display: none;">
                                                <img alt="" id="icons_thumb_<?php echo $icons?>" <?php if(isset($obj[$key]['icons_img_name'])) echo 'src="uploaded_images/thumbs/'.$obj[$key]['icons_img_name'].'?'.@$time.'"'; else echo 'src=""';?>/>
                                    </form></td></tr></table></td></tr>
                                          <tr>
                                            <td>TWITTER :<span id="checkbox_pos_soc"><input type="checkbox" name="twitter" <?php if($obj[$key]['twitter']=="true") echo "checked='checked'"?> /></span></td>
                                          </tr>
                                          <tr>
                                            <td>EMAIL LINK :<span id="checkbox_pos_soc"><input type="checkbox" name="email" onclick="fb_url('icons_<?php echo $icons?>',this)" <?php if($obj[$key]['email']!="") echo "checked='checked'"?> /></span></td>
                                          </tr>
                                          <tr id="email" <?php if($obj[$key]['email_subject']=="") echo "style='display: none;'"?>>
                                            <td>
                                            Email Subject
                                                <input type="text" name="email_link_url"  class="text_feild_cam"  value="<?php echo $obj[$key]['email_subject']?>"/>                </td>
                                          
                                          </tr>
                                          <tr>
                                            <td>Send SMS :<span id="checkbox_pos_soc"><input  type="checkbox"  name="send_sms" <?php if($obj[$key]['sms']=="true") echo "checked='checked'"?>/></span></td>
                                          </tr>
                                          <tr><td>Share Text: <small>(This will share on above enabled channels)</small><textarea class="text_area_cam" name="share_text"><?php echo $obj[$key]['share_text']?></textarea></td></tr>
                                          <tr><td>After Share URL: <small>(will redirect to below page)</small>
                                                <select name="after_share_url" class="text_feild_cam_select">
                                                <option value="">Select Any</option>
                                                <?php
                                                    foreach($arr_pages as $key_p => $val_p){
                                                        if($obj[$key]['after_share_url'] == $key_p)
                                                        {
                                                            $sel="selected='selected'";
                                                        }
                                                        else
                                                        $sel="";
                                                  echo "<option value='$key_p' $sel >$val_p</option>";      
                                                    }
                                                ?>
                                                </select>
                                                
                                          </td></tr>
                                                      <tr><td>Icons Alignment:
                                                         <select name="text_alignment" class="text_feild_cam_select">
                            <?php 
                            foreach($arr_align as $key_c => $val_c){
                                  if($obj[$key]['text_alignment'] == $key_c)
                                $sel='selected="selected"';
                                else
                                $sel="";
                            echo "<option value='$key_c' $sel>$val_c</option>";
                            }?>
                            </select>
                                                
                                          </td></tr>
                             <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                        </table>
                            
                                        </div>
                                        </li>
                                        <?php
                                        $icons++;
                                    };break;
                                           case "call":{
                                    $arr_color_picker[]="#call_$call";
                                        ?>
                                    <li class="widget drag_c_to_call" id="call_<?php echo $call?>">
                                     <div class="widget_hover">
                                              <img src="<?php if($obj[$key]['check']!="")echo $obj[$key]['check']; else echo 'images/enable.png'?>" class="check_widget" onclick="check_widget(this)">
                            
                                     <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                     </div>
                                        <div class="widget-head">
                                        <h3>
                                    Call Button
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_call_<?php echo $call?>" class="slider_area">
                                             <table width="100%" border="0" align="center">
                                              <tr>
                                                <td>
                                                Button Label
                                                <input type="text" name="call_button_lable" class="text_feild_cam" value="<?php echo $obj[$key]['call_lable']?>" />
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                Calling Number
                                                <input type="text" name="call_button_number" class="text_feild_cam" value="<?php echo $obj[$key]['call_number']?>" />
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                              Button BG Color
                                                <input type="color" name="call_button_bg_color"  class="color_picker text_feild_cam" value="<?php echo $obj[$key]['call_bg_color']?>" />
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                              Button Hover Color:
                                                <input type="color" name="call_hover_color"  class="color_picker text_feild_cam" value="<?php echo $obj[$key]['call_hover_color']?>" />
                                                </td>
                                              </tr>
                                                  <tr>
                                                <td>
                                              Fonts Color:
                                                <input type="color" name="fonts_color"  class="color_picker text_feild_cam" value="<?php echo $obj[$key]['fonts_color']?>" />
                                                </td>
                                              </tr>
                                                  <tr>
                                                <td>
                                              Fonts size:
                                                <input type="text" name="fonts_size"  class="text_feild_cam" value="<?php echo $obj[$key]['fonts_size']?>" />
                                                <div class="hint">Suggetsed size is 10-22</div>
                                                </td>
                                              </tr>
                                              <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                          </table>
                                        </div>
                                        </li>
                                        <?php
                                        $call++;
                                    };break;     
                                        case "timer":{
                                       $arr_calendar[]="end_time_$timer";     
                                    
                                        ?>
                            <li class="widget drag_content" id="timer_<?php echo $timer?>">
                             <div class="widget_hover">
                              <img alt="" src="<?php if($obj[$key]['check']!="")echo $obj[$key]['check']; else echo 'images/enable.png'?>" class="check_widget" onclick="check_widget(this)">
                            
                             <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                             </div>
                                        <div class="widget-head">
                                        <h3>
                                        Count Down Timer 
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_timer_<?php echo $timer?>" class="slider_area">
                                        
                                        <table width="100%" border="0" align="center">
                                             <tr>
                                                <td>
                                                End Time
                                                <input type="text" name="end_time"  class="text_feild_cam end_time" value="<?php echo $obj[$key]['end_time']?>" id="end_time_<?php echo $timer?>"/>
                                                </td>
                                              </tr>
                                                       <tr>
                                                <td>
                                                Timer Text
                                                <textarea class="text_area_cam" name="timer_text" ><?php echo $obj[$key]['timer_text'];?></textarea> 
                                                <span class="hint">You may use %timer% to display count down time</span>                   </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                Div Opacity&nbsp;&nbsp;&nbsp;Limit : 0.1 - 1
                                                <input type="text" name="tdiv_opacity"  class="text_feild_cam" value="<?php echo $obj[$key]['page_opacity']?>" />
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                Font Size&nbsp;&nbsp;&nbsp;PX is auto included.
                                                <input type="text" name="tfont_size"  class="text_feild_cam" value="<?php echo $obj[$key]['tfont_size']?>" />
                                                 </td>
                                              </tr>
                                              <tr>
                                                <td>
                                             Font Color
                                                <input type="color" name="tfont_color"  class="color_picker text_feild_cam" value="<?php echo $obj[$key]['tfont_color']?>" />
                                                 </td>
                                              </tr>
                             <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                          </table>
                            
                                        
                                        </div>
                                        </li>
                                        <?php
                                        $timer++;
                                    };break;          
                                      case "cart":{
                                             $arr_color_picker[]="#cart_$cart";    
                                        ?>
                                <li class="widget business_map" id="cart_<?php echo $cart?>">
                               <div class="widget_hover">
                                    <img alt="" src="<?php if($obj[$key]['check']!="")echo $obj[$key]['check']; else echo 'images/enable.png'?>" class="check_widget" onclick="check_widget(this)">
                            
                                 <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                 </div>
                                        <div class="widget-head">
                                        <h3>
                                        Add To Cart
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_cart_<?php echo $cart?>" class="slider_area">
                                             <table width="100%" border="0" align="center">
                                              <tr>
                                                <td>
                                                Button Label
                                                <input type="text" name="cart_lable" class="text_feild_cam" value="<?php echo $obj[$key]['cart_lable']?>"  />
                                                </td>
                                              </tr>
                                                  <tr>
                                                <td>
                                                Font Color:
                                                <input type="color" name="font_color" class="color_picker text_feild_cam"  value="<?php if(isset($obj[$key]['font_color'])) echo $obj[$key]['font_color']?>" />
                                                </td>
                                              </tr>
                                                    <tr>
                                                <td>
                                              Font Size: <small>(PX is auto included)</small>
                                              <input type="text" name="font_size" class="text_feild_cam"  value="<?php if(isset($obj[$key]['font_size'])) echo $obj[$key]['font_size']?>" />
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                              Landing Page Url
                                                <input type="text" name="cart_url" class="text_feild_cam" value="<?php echo $obj[$key]['cart_url']?>"  />
                                                </td>
                                              </tr>
                                             <tr><td>Selecct Button </td></tr>
                                               <tr>
                                                <td align="center">
                                                <select name="choose_button_type_<?php echo $cart?>" onchange="show_hide_option('slider_cart_<?php echo $cart?>','hide_options_cart',this)" class="text_feild_cam_select"><?php 
                            foreach($arr_button_type as $key_c =>$val_c){
                                if($obj[$key]['cart_button_type'] == $key_c)
                                $sel="selected='selected'";
                                else $sel="";
                            echo "<option value='$key_c' $sel >$val_c</option>";    
                            }
                            ?></select>  
                                                </td>
                                              </tr>
                                              <tr id="button_template" <?php if($obj[$key]['cart_button_type'] != "button_template") echo 'style="display: none;"';?> class="hide_options_cart">
                                                <td>
                                               Select button template 
                                              <select name="cart_button_template" onchange="show_button_template(this,'button_cart_<?php echo $cart?>')" class="text_feild_cam_select">
                                              <option value="">---Select Any---</option>
                                              <?php
                                            
                                               foreach($arr_buttons as $key_b => $val_b){
                                                   if($obj[$key]['cart_button_type'] == "button_template")
                                                  { if($obj[$key]['cart_button_template'] == $key_b)
                                                   {
                                                       $sel='selected="selected"';
                                                      $is_empty=$val_b['is_empty'];
                                                     // $test= $key_b."---".$obj[$key]['redeem_button_template'];                  
                                                   }
                                                   else 
                                                   {
                                                   $sel="";
                                                   }
                                               //  $test="test".$obj[$key]['redeem_button_template'];
                                                  }
                                                   echo '<option value="'.$key_b."++"."$val_b[is_empty]".'" '.$sel.'>'.$val_b['title'].'</option>';
                                               }   
                                              ?>
                                              </select>
                                            
                            
                                               <div id="button_cart_<?php echo $cart;?>" class="button_template"><img alt="" <?php if(isset($obj[$key]['cart_button_template'])) echo 'src="images/buttons/'.$obj[$key]['cart_button_template'].'"'; else echo 'src=""';?>/>
                                              <input type="hidden" name="is_empty" value="<?php echo $is_empty?>"/>
                                              </div>
                                        </td>
                                              </tr>
                                               <tr id="button_image" <?php if($obj[$key]['cart_button_type'] != "button_image") echo 'style="display: none;"';?> class="hide_options_cart"><td>           Upload Image :
                                                <form  method="POST" enctype="multipart/form-data">
                            
                            <input id="cart_button_image_<?php echo $cart?>" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;">
                            <button class="button" onclick="return ajaxFileUpload('cart_button_image_<?php echo $cart?>','cart_button_loading_<?php echo $cart?>','cart_button_thumb_<?php echo $cart?>','image');" style="margin-top: 5px;">Upload</button>
                                                <img id="cart_button_loading_<?php echo $cart?>" src="load9.gif" style="display: none;">
                                                <img alt="" id="cart_button_thumb_<?php echo $cart?>" <?php if(isset($obj[$key]['cart_button_image'])) echo 'src="uploaded_images/thumbs/'.$obj[$key]['cart_button_image'].'?'.@$time.'"'; else echo 'src=""';?>/>
                                    </form></td></tr>
                                     <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                          </table>
                                        </div>
                                        </li>
                                        <?php
                                        $cart++;
                                    };break;         
                                     case "scarcity":{
                                     $arr_color_picker[]="#scarcity_$scarcity";
                                        ?>
                                        <li class="widget business_map" id="scarcity_<?php echo $scarcity?>">
                                       <div class="widget_hover">
                                 <img alt="" src="<?php if($obj[$key]['check']!="")echo $obj[$key]['check']; else echo 'images/enable.png'?>" class="check_widget" onclick="check_widget(this)">
                                 <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                         </div>
                                        <div class="widget-head">
                                        <h3>
                                        Scarcity Widget 
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_scarcity_<?php echo $scarcity?>" class="slider_area">
                                                  <table width="100%" border="0" align="center">
                                              <tr>
                                                <td>
                                                Redemption Limit &nbsp;&nbsp;&nbsp; i.e : 10
                                                <input type="text" name="red_limit"  class="text_feild_cam" value="<?php echo $obj[$key]['red_limit']?>" />
                                                </td>
                                              </tr>
                                             <tr>
                                                <td>
                                                Redemption text
                                                <textarea name="red_text"  class="text_area_cam"><?php echo $obj[$key]['red_text']?></textarea>
                                                <span class="hint">You may use %redeem% to display how many redemptions are left</span>
                                                </td>
                                              </tr>
                                                 <tr>
                                                <td>
                                                Font Size&nbsp;&nbsp;&nbsp;PX is auto included.
                                                <input type="text" name="red_font_size" class="text_feild_cam" value="<?php echo $obj[$key]['red_font_size']?>" />
                                                </td>
                                              </tr>
                                                
                                              <tr>
                                                <td>
                                                Font Color
                                                <input type="color" name="red_font_color" class="color_picker red_font_color  text_feild_cam" value="<?php echo $obj[$key]['red_font_color']?>"  />
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                               <select name="text_alignment" class="text_feild_cam_select">
                            <?php 
                            foreach($arr_align as $key_c => $val_c){
                                  if($obj[$key]['text_alignment'] == $key_c)
                                $sel='selected="selected"';
                                else
                                $sel="";
                            echo "<option value='$key_c' $sel>$val_c</option>";
                            }?>
                            </select>
                                                </td>
                                              </tr>
                                    <tr>
                                                <td align="center">
                                            BG Image :
                                                <input  name="slider_scarcity_<?php echo $scarcity?>" onclick="show_hide_header_img_video('slider_scarcity_<?php echo $scarcity?>',this)" type="radio" value="red_image" <?php if(@$obj[$key]['red_bg_type'] == "red_image") echo 'checked="checked"';?>/>
                                            BG color :
                                 <input  name="slider_scarcity_<?php echo $scarcity?>" onclick="show_hide_header_img_video('slider_scarcity_<?php echo $scarcity?>',this)" type="radio" value="red_color" <?php if(@$obj[$key]['red_bg_type'] == "red_color") echo 'checked="checked"';?>/>
                                                </td>
                                              </tr>
                                              <tr id="red_color" <?php if(@$obj[$key]['red_bg_type'] != "red_color") echo 'style="display: none;"';?>>
                                                <td>
                                                BG Color
                                                <input type="color" name="red_bg_color" class="color_picker  text_feild_cam"  value="<?php echo $obj[$key]['red_bg_color']?>" />
                                                                    </td>
                                              </tr>
                                              <tr id="red_image" <?php if(@$obj[$key]['red_bg_type'] != "red_image") echo 'style="display: none;"';?>><td>           Upload Image :
                                                <form  method="POST" enctype="multipart/form-data">
                            
                            <input id="red_image_<?php echo $scarcity?>" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;">
                            <button class="button" onclick="return ajaxFileUpload('red_image_<?php echo $scarcity?>','red_loading_<?php echo $scarcity?>','red_thumb_<?php echo $scarcity?>','image');" style="margin-top: 5px;">Upload</button>
                                                <img id="red_loading_<?php echo $scarcity?>" src="load9.gif" style="display: none;">
                                                <img alt="" id="red_thumb_<?php echo $scarcity?>" <?php if(isset($obj[$key]['red_bg_image'])) echo 'src="uploaded_images/thumbs/'.$obj[$key]['red_bg_image'].'?'.@$time.'"'; else echo 'src=""';?>/>
                                    </form></td></tr>
                                         <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                          </table>
                                        </div>
                                        </li>
                                        <?php
                                        $scarcity++;
                                    };break;    
                                       case "facebook":{
                                    
                                        ?>
                                           <li class="widget business_map" id="facebook_<?php echo $facebook?>" >  
                                           <div class="widget_hover">
                                            <img alt="" src="<?php if($obj[$key]['check']!="")echo $obj[$key]['check']; else echo 'images/enable.png'?>" class="check_widget" onclick="check_widget(this)">
                                            <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                           </div>
                                        <div class="widget-head">
                                        <h3>
                                        Facebook Comments
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_facebook_<?php echo $facebook?>" class="slider_area">
                                             <table width="100%" border="0" align="center">
                                                 <tr><td><small> System will add comments plugin that lets people comment on your landing page using their Facebook account.</small></td></tr>
                                                 <tr>
                                                <td>
                                                No of Comments:
                                                <input type="text" name="posts"  class="text_feild_cam" value="<?php echo $obj[$key]['posts']; ?>" />
                                                </td>
                                              </tr>
                             <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                          </table>
                                        </div>
                                        </li>
                                        <?php
                                        $facebook++;
                                    };break;        
                                    
                                       case "map":{
                                    
                                        ?>
                                        <li class="widget business_map" id="map_<?php echo $map?>">
                                       <div class="widget_hover">
                            <img alt="" src="<?php if($obj[$key]['check']!="")echo $obj[$key]['check']; else echo 'images/enable.png'?>" class="check_widget" onclick="check_widget(this)">
                                         <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                         </div>
                                        <div class="widget-head">
                                        <h3>
                                        Map-Get Directions
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_map_<?php echo $map?>" class="slider_area">
                                             <table width="100%" border="0" align="center">
                                              <tr>
                                               <td>
                                              Enter Address: <small>(Enter address, then drag red marker on location to get exact address)</small>
                                                <textarea name="address"  class="text_feild_cam map_address"  id="map_address_<?php echo $map;?>" onfocus="load_map('<?php echo $map?>')" style="width: 100%; height: 70px;"><?php echo $obj[$key]['address']?></textarea><div id="wrapper_<?php echo $map?>" style="margin-left: 00px; margin-top: 10px;  position: absolute; display: none; z-index: 1;"><div id="map_div_<?php echo $map?>" style="border: 5px solid #ccc; width: 550px; height: 400px; background-color: #e2e2e2; overflow: hidden;"></div><img alt="" src="images/close_buttonc.png" style=" cursor: pointer; z-index: 2; position: absolute;" id="close_map_<?php echo $map?>" class="close_map"/></div>
                                             <input type="hidden" name="lat" value="<?php echo $obj[$key]['lat']?>"/>
                                             <input  type="hidden" name="lon" value="<?php echo $obj[$key]['lon']?>"/>   
                                                </td>
                                              </tr>
                            <tr><td> Zoom level: <input type="text" name="zoom"  class="text_feild_cam" value="<?php echo $obj[$key]['zoom']; ?>" />
                            <div class="hint">Zoom level range 1-16 suggested 8-14</div>
                                                </td>
                                              </tr>
                                              <tr><td> Add get direction: <small>(Enable to add Get Directions button on map)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="get_direction"  <?php if($obj[$key]['get_direction'] == "true") echo "checked='checked'";?>/></span>                    </td>
                                              </tr>
                                               <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                          </table>
                                        </div>
                                        </li>
                                        <?php
                                        $map++;
                                    };break;
                                     case "twitter":{
                                          $arr_color_picker[]="#twitter_$twitter";
                                        ?>
                                   <li class="widget business_map" id="twitter_<?php echo $twitter?>">  
                                   <div class="widget_hover">
                                    <img alt="" src="<?php echo $obj[$key]['check'];?>" class="check_widget" onclick="check_widget(this)">
                            
                                    <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                    </div>
                                        <div class="widget-head">
                                        <h3>
                                        Twitter feed
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_twitter_<?php echo $twitter?>" class="slider_area">
                                             <table width="100%" border="0" align="center">
                                              <tr>
                                                <td>
                                              Enter Username:
                                                <input name="username"  class="text_feild_cam" type="text"  value="<?php echo $obj[$key]['username']?>" />                  </td>
                                              </tr>
                                                  <tr>
                                                <td>
                                              No of tweets:
                                                <input name="tweets"  class="text_feild_cam" type="text"  value="<?php echo $obj[$key]['tweets']?>"  />                  </td>
                                              </tr>
                                              <tr><td>Select font color: <input type="color" name="t_fcolor"  value="<?php echo $obj[$key]['t_fcolor']?>" class="text_feild_cam color_picker" /></td></tr>
                            <tr><td>Select font size: <small>(PX is auto included)</small> <input type="text" name="t_fsize"  value="<?php echo $obj[$key]['t_fsize']?>" class="text_feild_cam" /></td></tr>
                            <tr><td>Select background color: <input type="color" name="t_bcolor"  value="<?php echo $obj[$key]['t_bcolor']?>" class="text_feild_cam color_picker"/></td></tr>
                            <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>              
                                          </table>
                                        </div>
                                        </li>
                                        <?php
                                        $twitter++;
                                    };break;
                                    
                                    case "webform":{
                                          $arr_color_picker[]="#webform_$webform";
                                        ?>
                                   <li class="widget business_map" id="webform_<?php echo $webform?>">  
                                   <div class="widget_hover">
                                    <img alt="" src="<?php echo $obj[$key]['check'];?>" class="check_widget" onclick="check_widget(this)">
                            
                                    <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                    </div>
                                        <div class="widget-head">
                                        <h3>
                                        Webform
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_webform_<?php echo $webform?>" class="slider_area">
                                             <table width="100%" border="0" align="center">
                                              <tr>
                                                <td>
                                                Text above input fields
                                                <textarea name="heading"  class="text_feild_cam form-control" ><?php echo @$obj[$key]['heading']?></textarea>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                              Enter Name Label
                                                <input name="name"  class="text_feild_cam" type="text"  value="<?php echo @$obj[$key]['name']?>" />                  </td>
                                              </tr>
                                                  <tr>
                                                <td>
                                              Enter Email Label:
                                                <input name="email"  class="text_feild_cam" type="email"  value="<?php echo @$obj[$key]['email']?>"  />                  </td>
                                              </tr>
                                              <tr>
                                                <td>
                                              Enter Number Label:
                                                <input name="number"  class="text_feild_cam" type="text"  value="<?php echo @$obj[$key]['number']?>"  />                  </td>
                                              </tr>
                                              <tr>
                                                <td>
                                              Enter Birthday Label:
                                                <input name="birthday"  class="text_feild_cam" type="text"  value="<?php echo @$obj[$key]['birthday']?>"  />                  </td>
                                              </tr>
                                              <tr>
                                                <td>
                                              Enter Anniversary Label:
                                                <input name="anniversary"  class="text_feild_cam" type="text"  value="<?php echo @$obj[$key]['anniversary']?>"  />                  </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                    Campaign
                                                    <select name="group_id" class="text_feild_cam" >
                                                    
                                                    <option value="">Select Any</option>
                                                    <?php
                                                        foreach($arr_campaigns as $key_p => $val_p){
                                                            if(@$obj[$key]['group_id'] == $key_p)
                                                            {
                                                                $sel="selected='selected'";
                                                            }
                                                            else
                                                            $sel="";
                                                      echo "<option value='$key_p' $sel >$val_p</option>";      
                                                        }
                                                    ?>
                                                        
                                                    </select> 
                                                </td>
                                              </tr>
                            <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>              
                                          </table>
                                        </div>
                                        </li>
                                        <?php
                                        $webform++;
                                    };break;
                                            case "fonts":{
                                                $arr_color_picker[]="#fonts_$fonts";
                                    
                                        ?>
                                               <li class="widget business_map" id="fonts_<?php echo $fonts?>"> 
                                               <div class="widget_hover">
                                   <img alt="" src="<?php if($obj[$key]['check']!="")echo $obj[$key]['check']; else echo 'images/enable.png'?>" class="check_widget" onclick="check_widget(this)"> 
                                                <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)"> 
                                                  </div>
                                        <div class="widget-head">
                                        <h3>
                                      Headline
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_fonts_<?php echo $fonts?>" class="slider_area">
                                                    <table border=0>
                                        <tr><td>Headline Text: <textarea class="text_area_cam" name="font_text"><?php echo $obj[$key]['font_text']?></textarea></td></tr>
                                        <tr><td>Font size: <small>(PX is auto included)</small> <input type="text" name="font_size" class="text_feild_cam" value="<?php echo $obj[$key]['font_size'];?>" /></td></tr>
                                        <tr><td>Font color: <input type="color" name="font_color" class="text_feild_cam color_picker" value="<?php echo $obj[$key]['font_color']?>" /></td></tr>
                            <tr><td>Head Text ALignment:</td></tr>
                            <tr><td><select name="text_alignment" class="text_feild_cam_select">
                            <?php 
                            foreach($arr_align as $key_c => $val_c){
                                if($obj[$key]['text_alignment'] == $key_c)
                                $sel='selected="selected"';
                                else
                                $sel="";
                            echo "<option value='$key_c' $sel>$val_c</option>";
                            }?>
                            </select></td></tr>
                                                   <tr>
                                                <td align="center">
                                       BG Image :
                                                <input  name="slider_fonts_<?php echo $fonts?>" onclick="show_hide_header_img_video('slider_fonts_<?php echo $fonts?>',this)" type="radio" value="font_image" <?php if(@$obj[$key]['font_bg_type'] == "font_image") echo "checked='checked'";?>/>
                                            BG color :
                                 <input  name="slider_fonts_<?php echo $fonts?>" onclick="show_hide_header_img_video('slider_fonts_<?php echo $fonts?>',this)" type="radio" value="font_color"  <?php if(@$obj[$key]['font_bg_type'] == "font_color") echo "checked='checked'";?>/>
                                                </td>
                                              </tr>
                                              <tr id="font_color"  <?php if(@$obj[$key]['font_bg_type'] != "font_color") echo'style="display: none;"'; ?>>
                                                <td>
                                                BG Color
                                                <input type="color" name="font_bg_color" class="color_picker text_feild_cam"  value="<?php if(isset($obj[$key]['font_bg_color'])) echo $obj[$key]['font_bg_color']?>" />
                                                                    </td>
                                              </tr>
                                              <tr id="font_image"  <?php if(@$obj[$key]['font_bg_type'] != "font_image") echo'style="display: none;"'; ?>><td>  Upload Image :
                                                <form  method="POST" enctype="multipart/form-data">
                            
                            <input id="font_image_<?php echo $fonts?>" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;">
                            <button class="button" onclick="return ajaxFileUpload('font_image_<?php echo $fonts?>','font_loading_<?php echo $fonts?>','font_thumb_<?php echo $fonts?>','image');" style="margin-top: 5px;">Upload</button>
                                                <img id="font_loading_<?php echo $fonts?>" src="load9.gif" style="display: none;">
                                                <img alt="" id="font_thumb_<?php echo $fonts?>" <?php if(isset($obj[$key]['font_bg_image'])) echo 'src="uploaded_images/thumbs/'.$obj[$key]['font_bg_image'].'?'.@$time.'"'; else echo 'src=""';?>/>
                                    </form></td></tr>
                                    <tr><td>Fonts Sorting:<select class="text_feild_cam_select" name="get_updated_fonts" onchange="get_updated_fonts('slider_fonts_<?php echo $fonts?>',this);">
                                    <?php 
                                  
                                    foreach($arr_fonts_sort as $key_f => $val_f){
                                        echo "<option value='$key_f'>$val_f</option>";
                                    }
                                    ?>
                                    </select></td></tr>
                                        <tr><td>
                                   <select class="text_feild_cam_select" name="google_fonts" onchange="show_google_font('<?php echo $fonts?>',this)">
                                     <option value="">-----Select Any-----</option>
                                     <?php
                                         $fonts_arr=json_decode($fonts_json,true);
                                         foreach($fonts_arr as $key_fonts => $val_fonts){
                                             if($obj[$key]['google_font'] == $val_fonts)
                                             $sel="selected='selected'";
                                             else
                                             $sel="";
                                         ?>
                                         <option value="<?php echo $val_fonts?>" <?php echo $sel?>><?php echo $val_fonts?></option>
                                         <?php    
                                         }
                                     ?>
                                     </select>
                                     <?php
                                         if(isset($obj[$key]['google_font'])){
                                     ?>
                                     <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo $obj[$key]['google_font']?>" />
                                       <?php  }
                                     ?>
                                         <div id="font_sample_<?php echo $fonts?>" style="<?php if(isset($obj[$key]['google_font'])) echo 'font-family: '.$obj[$key]['google_font'].";";?>font-size: 30px; border: 1px solid #ccc; padding: 4px; border-radius: 5px; margin-top: 5px;">Sample Text</div>
                                        </td></tr>
                                                  <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                        </table>
                                </div>
                                        </li>
                                        <?php
                                        $fonts++;
                                    };break;
                                                  case "loyalty":{
                                               $arr_color_picker[]="#loyalty_$loyalty";
                                    $arr_calendar_only_date[]="keyword_date_$loyalty";  
                                        ?>
                                               <li class="widget business_map" id="loyalty_<?php echo $loyalty?>"> 
                                               <div class="widget_hover">
                                   <img src="<?php if($obj[$key]['check']!="")echo $obj[$key]['check']; else echo 'images/enable.png'?>" class="check_widget" onclick="check_widget(this)"> 
                                                <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)"> 
                                                  </div>
                                        <div class="widget-head">
                                        <h3>
                                      Loyalty
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_loyalty_<?php echo $loyalty?>" class="slider_area">
                                                    <table border=0>
                                        <tr>
                                                <td align="center">
                                            BG Image :
                                                <input  name="loyalty_bg_<?php echo $loyalty?>" onclick="show_hide_header_img_video('slider_loyalty_<?php echo $loyalty?>',this)" type="radio" value="loyalty_image" <?php if(@$obj[$key]['loyalty_bg_type'] == "loyalty_image") echo 'checked="checked"';?>/>
                                            BG Color :
                                 <input  name="loyalty_bg_<?php echo $loyalty?>" onclick="show_hide_header_img_video('slider_loyalty_<?php echo $loyalty?>',this)" type="radio" value="loyalty_bgcolor" <?php if(@$obj[$key]['loyalty_bg_type'] == "loyalty_bgcolor") echo 'checked="checked"';?>/>
                                                </td>
                                              </tr>
                                  
                                              <tr id="loyalty_bgcolor" <?php if(@$obj[$key]['loyalty_bg_type'] !="loyalty_bgcolor" )echo 'style="display: none;"';?>>
                                                <td>
                                               
                                                <input type="color" name="loyalty_bgcolor" class="text_feild_cam color_picker"  value="<?php if(isset($obj[$key]['loyalty_bgcolor'])) echo $obj[$key]['loyalty_bgcolor']?>" />
                                                                    </td>
                                              </tr>
                                              <tr id="loyalty_image" <?php if(@$obj[$key]['loyalty_bg_type'] != "loyalty_image") echo 'style="display: none;"';?>><td>           Upload Image :
                                                <form  method="POST" enctype="multipart/form-data">
                            
                            <input id="loyalty_image_<?php echo $loyalty?>" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;">
                            <button class="button" onclick="return ajaxFileUpload('loyalty_image_<?php echo $loyalty?>','loyalty_loading_<?php echo $loyalty?>','loyalty_thumb_<?php echo $loyalty?>','image');" style="margin-top: 5px;">Upload</button>
                                                <img id="loyalty_loading_<?php echo $loyalty?>" src="load9.gif" style="display: none;">
                                                <img alt="" id="loyalty_thumb_<?php echo $loyalty?>" <?php if(isset($obj[$key]['loyalty_image'])) echo 'src="uploaded_images/thumbs/'.$obj[$key]['loyalty_image'].'?'.@$time.'"'; else echo 'src=""';?>/>
                                    </form></td></tr>
                                    <tr><td>Font Color: <input name="loyalty_font_color" type="color" class="text_feild_cam color_picker" value="<?php echo $obj[$key]['loyalty_font_color']?>" ></td></tr>
                                    <tr><td>Font Size: <small>(PX is auto included)</small> <input name="loyalty_font_size" type="text" class="text_feild_cam" value="<?php echo $obj[$key]['loyalty_font_size']?>" ></td></tr>
                                    <tr><td>Text Alignment: <select name="text_alignment" class="text_feild_cam_select">
                            <?php 
                            foreach($arr_align as $key_c => $val_c){
                                if($obj[$key]['text_alignment'] == $key_c)
                                $sel='selected="selected"';
                                else
                                $sel="";
                            echo "<option value='$key_c' $sel>$val_c</option>";
                            }?>
                            </select></td></tr>
                                    <tr><td>Text above CodeBox: <textarea name="text_above_code"  class="text_area_cam"  ><?php echo $obj[$key]['text_above_code']?></textarea></td></tr>
                                    <tr><td>Codes Required: <input type="text" name="codes_required"  class="text_feild_cam" value="<?php echo $obj[$key]['codes_required']?>" /></td></tr>
                                        <tr><td>Invalid Code Prompt: <textarea name="invalid_prompt"  class="text_area_cam"  ><?php echo $obj[$key]['invalid_prompt']?></textarea></td></tr>
                                            <tr><td>Loser Prompt: <textarea name="looser_prompt"  class="text_area_cam"  ><?php echo $obj[$key]['looser_prompt']?></textarea><div class="hint">You can use %togo% in Loser Prompt to display how many more codes user must enter.</div></td></tr>
                                            <tr><td>       Winner Page:
                                                <select name="reward_page" class="text_feild_cam_select">
                                                <option value="">Select Any</option>
                                                <?php
                                                    foreach($arr_pages as $key_p => $val_p){
                                                        if($obj[$key]['reward_page'] == $key_p)
                                                        {
                                                            $sel="selected='selected'";
                                                        }
                                                        else
                                                        $sel="";
                                                  echo "<option value='$key_p' $sel >$val_p</option>";      
                                                    }
                                                ?>
                                                </select></td></tr>
                                                <tr><td><fieldset><legend>Keywords</legend><table border="0" >
                                                <tr><td>Date: </td></tr><tr><td><input type="text" id="keyword_date_<?php echo $loyalty?>" name="keyword_date" class="text_feild_small"></td></tr>
                                                <tr><td>Keywords: </td></tr><tr><td><input type="text" name="keyword" class="text_feild_small"></td></tr>
                                                <tr><td>Keyword CSV: </td></tr><tr><td><input type="file" id="keyword_csv_<?php echo  $loyalty?>" name="keyword_csv" onchange="ajaxupload_csv('slider_loyalty_<?php echo $loyalty?>',this)" style="width: 100%;" size="10"></td></tr>
                                                <tr><td><input type="button" name="add_keyword" value="Add" onclick="add_keywords('slider_loyalty_<?php echo $loyalty?>',this);"><a href="images/sample.csv" class="link">Download sample CSV</a></td></tr></table>
                                                </fieldset>     
                                        <div id="keywords_list" style="max-height: 100px; overflow: auto; margin-top: 5px;"><table class="table" style="width: 100%">
                            <tr><td><b>Date</b></td><td colspan="2"><b>KeyWord</b></td></tr>
                            
                            
                            <?php 
                            if(is_array($obj[$key]['keywords_data']) && count($obj[$key]['keywords_data'])>0)
                            {
                                foreach($obj[$key]['keywords_data'] as $key_k => $val_k)
                                {
                                    echo "<tr class='keywords_data'><td>".key($val_k)." <td>".$val_k[key($val_k)]."</td><td align='right'><img src='images/close.png'  onclick='delete_keyword(this);' id='add'></td></tr>";
                                }
                            }
                            ?>
                            </table></div>
                                                </td></tr>
                                                <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border']=="true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                
                                    </table>
                                </div>
                                        </li>
                                        <?php
                                        $loyalty++;
                                    };break;
                                     case "scratch":{
                                          $arr_color_picker[]="#scratch_$scratch";
                                      ///   print_r($obj[$key]);
                                                    ?>
                                   <li class="widget business_map" id="scratch_<?php echo $scratch?>">  
                                   <div class="widget_hover">
                                    <img alt="" src="<?php echo $obj[$key]['check'];?>" class="check_widget" onclick="check_widget(this)">
                            
                                    <img src="images/close_buttonc.png" class="delete_widget" onclick="delete_widget(this)">
                                    </div>
                                        <div class="widget-head">
                                        <h3>
                                        Scratch'N Share
                                        </h3><span class="drop_arrow" onclick="make_doggle(this)"></span>
                                        </div>
                                        <div id="slider_scratch_<?php echo $scratch?>" class="slider_area">
                                             <table width="100%" border="0" align="center">
                             <tr><td><strong>Background Image</strong> <small>(result image, will display after scratch)</small></td></tr> <tr> <td align="center"> Upload Image : <input  name="slider_scratch_<?php echo $scratch?>" onclick="show_hide_header_img_video('slider_scratch_<?php echo $scratch?>',this)" type="radio" value="scratch_bg_image" <?php if($obj[$key]['scratch_bg_type'] == "image") echo 'checked="checked"';?>/> Image URL : <input  name="slider_scratch_<?php echo $scratch?>" onclick="show_hide_header_img_video('slider_scratch_<?php echo $scratch?>',this)" type="radio" value="scratch_bg_url" <?php if($obj[$key]['scratch_bg_type'] == "url") echo 'checked="checked"';?>/> </td> </tr>  
                             <tr id="scratch_bg_url" <?php if($obj[$key]['scratch_bg_type'] !="url" )echo 'style="display: none;"';?>> <td> Image URL <input type="text" name="scratch_bg_url" class="text_feild_cam"  value="<?php if(isset($obj[$key]['scratch_bg_url'])) echo $obj[$key]['scratch_bg_url']?>" /> </td> </tr> 
                             <tr id="scratch_bg_image" <?php if($obj[$key]['scratch_bg_type'] != "image") echo 'style="display: none;"';?>><td> Upload Image : <form  method="POST" enctype="multipart/form-data">  <input id="scratch_bg_<?php echo $scratch?>" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload('scratch_bg_<?php echo $scratch?>','scratch_bg_loading_<?php echo $scratch?>','scratch_bg_thumb_<?php echo $scratch?>','image');" style="margin-top: 5px;">Upload</button> <img id="scratch_bg_loading_<?php echo $scratch?>" src="load9.gif" style="display: none;"> <img alt="" id="scratch_bg_thumb_<?php echo $scratch?>" <?php if(isset($obj[$key]['scratch_bg_image'])) echo 'src="uploaded_images/thumbs/'.$obj[$key]['scratch_bg_image'].'?'.@$time.'"'; else echo 'src=""';?>/> </form></td></tr>
                              <tr><td><strong>Foreground Image</strong><small>(Image to scratch)</small></td></tr> <tr> <td align="center"> Upload Image : <input  name="scratch_fg_<?php echo $scratch?>" onclick="show_hide_header_img_video('slider_scratch_<?php echo $scratch?>',this)" type="radio" value="scratch_fg_image" <?php if($obj[$key]['scratch_fg_type'] == "image") echo 'checked="checked"';?>/> Image URL : <input  name="scratch_fg_<?php echo $scratch?>" onclick="show_hide_header_img_video('slider_scratch_<?php echo $scratch?>',this)" type="radio" value="scratch_fg_url" <?php if($obj[$key]['scratch_fg_type'] == "url") echo 'checked="checked"';?>/> </td> </tr>  
                             <tr id="scratch_fg_url" <?php if($obj[$key]['scratch_fg_type'] !="url" )echo 'style="display: none;"';?>> <td> Image URL <input type="text" name="scratch_fg_url" class="text_feild_cam"  value="<?php if(isset($obj[$key]['scratch_fg_url'])) echo $obj[$key]['scratch_fg_url']?>" /> </td> </tr> 
                             <tr id="scratch_fg_image" <?php if($obj[$key]['scratch_fg_type'] != "image") echo 'style="display: none;"';?>><td> Upload Image : <form  method="POST" enctype="multipart/form-data">  <input id="scratch_fg_<?php echo $scratch?>" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;"> <button class="button" onclick="return ajaxFileUpload('scratch_fg_<?php echo $scratch?>','scratch_fg_loading_<?php echo $scratch?>','scratch_fg_thumb_<?php echo $scratch?>','image');" style="margin-top: 5px;">Upload</button> <img id="scratch_fg_loading_<?php echo $scratch?>" src="load9.gif" style="display: none;"> <img alt="" id="scratch_fg_thumb_<?php echo $scratch?>" <?php if(isset($obj[$key]['scratch_fg_image'])) echo 'src="uploaded_images/thumbs/'.$obj[$key]['scratch_fg_image'].'?'.@$time.'"'; else echo 'src=""';?>/> </form></td></tr>
                            <tr><td><strong>Widget Background</strong></td></tr>
                              <tr><td align="center">
                                            BG Image :
                                                <input  name="scratch_bg_<?php echo $scratch?>" onclick="show_hide_header_img_video('slider_scratch_<?php echo $scratch?>',this)" type="radio" value="scratch_image" <?php if($obj[$key]['widget_bg_type'] == "scratch_image") echo 'checked="checked"';?>/>
                                            BG Color :
                                 <input  name="scratch_bg_<?php echo $scratch?>" onclick="show_hide_header_img_video('slider_scratch_<?php echo $scratch?>',this)" type="radio" value="scratch_bgcolor" <?php if($obj[$key]['widget_bg_type'] == "scratch_bgcolor") echo 'checked="checked"';?>/>
                                                </td>
                                              </tr>
                                 <tr id="scratch_bgcolor" <?php if($obj[$key]['widget_bg_type'] !="scratch_bgcolor" )echo 'style="display: none;"';?>>
                                                <td>
                                                <input type="color" name="scratch_bgcolor" class="text_feild_cam color_picker"  value="<?php if(isset($obj[$key]['scratch_bgcolor'])) echo $obj[$key]['scratch_bgcolor']?>" />
                                                                    </td>
                                              </tr>
                                              <tr id="scratch_image" <?php if($obj[$key]['widget_bg_type'] != "scratch_image") echo 'style="display: none;"';?>><td>           Upload Image :
                                                <form  method="POST" enctype="multipart/form-data">
                            
                            <input id="scratch_image_<?php echo $scratch?>" type="file" size="7" name="fileToUpload" style="margin-top: 5px; width: 140px;">
                            <button class="button" onclick="return ajaxFileUpload('scratch_image_<?php echo $scratch?>','scratch_loading_<?php echo $scratch?>','scratch_thumb_<?php echo $scratch?>','image');" style="margin-top: 5px;">Upload</button>
                                                <img id="scratch_loading_<?php echo $scratch?>" src="load9.gif" style="display: none;">
                                                <img alt="" id="scratch_thumb_<?php echo $scratch?>" <?php if(isset($obj[$key]['scratch_image'])) echo 'src="uploaded_images/thumbs/'.$obj[$key]['scratch_image'].'?'.@$time.'"'; else echo 'src=""';?>/>
                                    </form></td></tr> 
                                    
                             <tr><td><b>Reveal Radius:</b> </td></tr><tr><td><input type="text" name="reveal_radius" class="text_feild_cam" value="<?php echo $obj[$key]['reveal_radius']?>"><div class="hint">Suggested value(15-30)</div></td></tr>
                             <tr><td><b>Auto show after:</b> <small>(background image will immediately display when scratch reaches selected percentage)</small> </td></tr><tr><td><input type="text" name="auto_show_after" class="text_feild_cam" value="<?php echo $obj[$key]['auto_show_after']?>"><div class="hint">In percentage- suggested value(80-95)</div></td></tr>
                             <tr><td>      <b>After scratch Page:</b> <small>(after scratch on background image click, page will redirect to selected page)</small>
                                                <select name="scratch_page" class="text_feild_cam_select">
                                                <option value="">Select Any</option>
                                                <?php
                                                    foreach($arr_pages as $key_p => $val_p){
                                                        if($obj[$key]['scratch_page'] == $key_p)
                                                        {
                                                            $sel="selected='selected'";
                                                        }
                                                        else
                                                        $sel="";
                                                  echo "<option value='$key_p' $sel >$val_p</option>";      
                                                    }
                                                ?>
                                                </select></td></tr>
                                 <tr><td><strong>Image Alignment:</strong><select name="text_alignment" class="text_feild_cam_select">
                            <?php 
                            foreach($arr_align as $key_c => $val_c){
                                if($obj[$key]['text_alignment'] == $key_c)
                                $sel='selected="selected"';
                                else
                                $sel="";
                            echo "<option value='$key_c' $sel>$val_c</option>";
                            }?>
                            </select></td></tr>
                              <tr><td>No Border: <span id="checkbox_pos_soc"><input type="checkbox" name="no_border"  <?php if($obj[$key]['no_border'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Transparent BG:  <small>(check to enable parent background)</small> <span id="checkbox_pos_soc"><input type="checkbox" name="transparent_bg"  <?php if($obj[$key]['transparent_bg'] == "true") echo "checked='checked'";?>/></span></td></tr>
                             <tr><td>Use Orginal Dimension: <span id="checkbox_pos_soc"><input type="checkbox" name="orig_dimensions"  <?php if($obj[$key]['orig_dimensions'] == "true") echo "checked='checked'";?>/></span></td></tr>
                                          </table>
                                        </div>
                                        </li>
                                        <?php
                                        $scratch++;
                                    };break;
                                }
                            }
                                }
                                        ?>
                                        
                                             
                                        </ul>
                                            <div class="text-right">
                                                <input type="button"  id="save_page" class="submit_settings btn btn-success" value="Save" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--- Second slide -->
                            
                            
                                   
                            </div> 
                            
                            <!--End Create Campaign--->
                                </div>
                            <div style="width:960px; height:35px; position:relative; float:left; display:block;"></div> 
                            <!---List All Campaigns-->
                            <script>
                            var color_picker_json=<?php echo json_encode($arr_color_picker)?>;
                            /////////////////////initialize colopicker
                            /*
                            $.each(color_picker_json,function(key,val){
                                      $(val+" .color_picker").ColorPicker({
                            onSubmit: function(hsb, hex, rgb, el) {
                            $(el).val(hex);
                            $(el).ColorPickerHide();},
                             onBeforeShow: function () {
                            $(this).ColorPickerSetColor(this.value);
                            }
                            });    
                            });
                            */
                            
                            var text_editor_json=<?php echo json_encode($arr_editor)?>;
                            console.log(text_editor_json);
                            /////////////////////initialize texteditor
                            $.each(text_editor_json,function(key,val){
                                         bkLib.onDomLoaded(function() {
                                         $("#"+val).css({height: '100px',width:'575px'});
                              new nicEditor({fullPanel : true,iconsPath : 'images/nicEditIcons-latest.gif'}).panelInstance(val);
                            });   
                            });
                            var calendar_json=<?php echo json_encode($arr_calendar)?>;
                            //console.log(calendar_json);
                            /////////////////////initialize datepicker
                            $.each(calendar_json,function(key,val){
                              var myCalendar;
                                myCalendar = new dhtmlXCalendarObject([val]);
                               myCalendar= myCalendar.setDateFormat("%d/%m/%Y %H:%i");   
                            })
                            var calendar_json_only_date=<?php echo json_encode($arr_calendar_only_date)?>;
                            //console.log(calendar_json_only_date);
                            /////////////////////initialize datepicker only date
                            $.each(calendar_json_only_date,function(key,val){
                              var myCalendar;
                                myCalendar = new dhtmlXCalendarObject([val]);
                               myCalendar= myCalendar.setDateFormat("%Y-%m-%d");   
                            });
                            </script>
                                 
                            <!--End List all campaigns---->
                            </div>
                            
                            
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<?php include_once("footer.php");?>
<script type="text/javascript" src="scripts/js/parsley.min.js"></script> 
<script src="scripts/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
	
    function slideToggleMainSection(obj,section,chkBox){
		var html = $(obj).html();
		var check = html.indexOf("fa-plus");
		if(check=="-1"){
			$(obj).html('<i class="fa fa-plus" title="Close" style="color:#FFF !important; float:right !important"></i>');
			$('.'+section).hide('slow');
			//if(chkBox!=''){
				//$('.'+chkBox+'').prop("checked",false);
			//}
		}else{
			$(obj).html('<i class="fa fa-minus" title="Open" style="color:#FFF !important; float:right !important"></i>');
			$('.'+section).show('slow');
			//if(chkBox!=''){
				//$('.'+chkBox+'').prop("checked",true);
			//}
		}
	}
    
    function switchTimeDropDown(obj){
		if($(obj).val()=='0'){
			$(obj).parent().find('.timeDropDown').css('display','none');
			$(obj).parent().find('.hoursDropDown').css('display','inline');
		}else{
			$(obj).parent().find('.timeDropDown').css('display','inline');
			$(obj).parent().find('.hoursDropDown').css('display','none');
		}
	}
	function slideToggleInnerSection(obj,eleMent){
		if($(obj).is(":checked")==true){
			$('.'+eleMent+'').show('slow');
		}else{
			$('.'+eleMent+'').hide('slow');
		}
	}
    
    function slideToggleBeaconSection(obj,eleMent){
		if(eleMent=='campaignBeaconCouponSection'){
			$('.campaignBeaconCouponSection').show('slow');
            $('.campaignBeaconURLSection').hide('slow');
		}else{
			$('.campaignBeaconCouponSection').hide('slow');
            $('.campaignBeaconURLSection').show('slow');
		}
	}
	
	function removeFollowUp(obj){
		if(confirm("Are you sure you want to remove this follow up?")){
			obj.closest('.delay_table').remove('slow');
		}
	}
	function followUpHtml(){
		var timeOption = '<?php echo @$timeOptions?>';
		var html = '<table width="100%" class="delay_table">';
		html += '<tr><td colspan="2"><hr style="background-color: #7e57c2 !important;height:1px;margin: 15px;"></td></tr>';
		html += '<tr><td width="25%">Select Days/Time</td><td><input type="text" class="form-control numericOnly" style="width:auto !important; display:inline !important" placeholder="Days delay..." name="delay_day[]" value="0" onblur="switchTimeDropDown(this)">&nbsp;<select class="form-control timeDropDown" style="width:48% !important; display:none !important" name="delay_time[]">'+timeOption+'</select><select class="form-control hoursDropDown" style="width:48% !important; display:inline" name="delay_time_hours[]"><?php echo @$options?></select></td></tr>';
		html += '<tr><td>Message</td><td><textarea name="delay_message[]" class="form-control textCounter"></textarea><span class="showCounter"><span class="showCount"><?php echo @$maxLength?></span> Characters left</span></td></tr>';
		html += '<tr><td>Attach Media</td><td><input type="file" name="delay_media[]" style="display:inline !important"><span class="fa fa-trash" style="color:red;float:right;margin:10px;cursor:pointer" title="Remove Message" onclick="removeFollowUp(this)"></span></td></tr></table>';
		return html;
	}
	function addMoreFollowUpMsg(){
		var html = followUpHtml();
		$('#followUpContainer').append('<div>'+html+'</div>');
		$('.showCounter').hide();
	}	
	$(document).ready(function(){
		$('form').parsley();
		//$('.datepicker').datepicker();
        
        
        jQuery('#start_date, #end_date').datepicker({
			autoclose: true,
			todayHighlight: true
		});
        
	});
</script>