<?php
$json = json_decode(file_get_contents("db/data.json"), TRUE);
//print_r($json);

$industry = array(
	"industry:47" => "Accounting",
	"industry:94" => "Airlines/Aviation",
	"industry:120" => "Alternative Dispute Resolution",
	"industry:125" => "Alternative Medicine",
	"industry:127" => "Animation",
	"industry:19" => "Apparel &amp; Fashion",
	"industry:50" => "Architecture &amp; Planning",
	"industry:111" => "Arts &amp; Crafts",
	"industry:53" => "Automotive",
	"industry:52" => "Aviation &amp; Aerospace",
	"industry:41" => "Banking",
	"industry:12" => "Biotechnology",
	"industry:36" => "Broadcast Media",
	"industry:49" => "Building Materials",
	"industry:138" => "Business Supplies &amp; Equipment",
	"industry:129" => "Capital Markets",
	"industry:54" => "Chemicals",
	"industry:90" => "Civic &amp; Social Organization",
	"industry:51" => "Civil Engineering",
	"industry:128" => "Commercial Real Estate",
	"industry:118" => "Computer &amp; Network Security",
	"industry:109" => "Computer Games",
	"industry:3" => "Computer Hardware",
	"industry:5" => "Computer Networking",
	"industry:4" => "Computer Software",
	"industry:48" => "Construction",
	"industry:24" => "Consumer Electronics",
	"industry:25" => "Consumer Goods",
	"industry:91" => "Consumer Services",
	"industry:18" => "Cosmetics",
	"industry:65" => "Dairy",
	"industry:1" => "Defense &amp; Space",
	"industry:99" => "Design",
	"industry:132" => "E-learning",
	"industry:69" => "Education Management",
	"industry:112" => "Electrical &amp; Electronic Manufacturing",
	"industry:28" => "Entertainment",
	"industry:86" => "Environmental Services",
	"industry:110" => "Events Services",
	"industry:76" => "Executive Office",
	"industry:122" => "Facilities Services",
	"industry:63" => "Farming",
	"industry:43" => "Financial Services",
	"industry:38" => "Fine Art",
	"industry:66" => "Fishery",
	"industry:34" => "Food &amp; Beverages",
	"industry:23" => "Food Production",
	"industry:101" => "Fundraising",
	"industry:26" => "Furniture",
	"industry:29" => "Gambling &amp; Casinos",
	"industry:145" => "Glass, Ceramics &amp; Concrete",
	"industry:75" => "Government Administration",
	"industry:148" => "Government Relations",
	"industry:140" => "Graphic Design",
	"industry:124" => "Health, Wellness &amp; Fitness",
	"industry:68" => "Higher Education",
	"industry:14" => "Hospital &amp; Health Care",
	"industry:31" => "Hospitality",
	"industry:137" => "Human Resources",
	"industry:134" => "Import &amp; Export",
	"industry:88" => "Individual &amp; Family Services",
	"industry:147" => "Industrial Automation",
	"industry:84" => "Information Services",
	"industry:96" => "Information Technology &amp;Services",
	"industry:42" => "Insurance",
	"industry:74" => "International Affairs",
	"industry:141" => "International Trade &amp;Development",
	"industry:6" => "Internet",
	"industry:45" => "Investment Banking",
	"industry:46" => "Investment Management",
	"industry:73" => "Judiciary",
	"industry:77" => "Law Enforcement",
	"industry:9" => "Law Practice",
	"industry:10" => "Legal Services",
	"industry:72" => "Legislative Office",
	"industry:30" => "Leisure, Travel &amp; Tourism",
	"industry:85" => "Libraries",
	"industry:116" => "Logistics &amp; Supply Chain",
	"industry:143" => "Luxury Goods &amp; Jewelry",
	"industry:55" => "Machinery",
	"industry:11" => "Management Consulting",
	"industry:95" => "Maritime",
	"industry:97" => "Market Research",
	"industry:80" => "Marketing &amp; Advertising",
	"industry:135" => "Mechanical Or Industrial Engineering",
	"industry:126" => "Media Production",
	"industry:17" => "Medical Device",
	"industry:13" => "Medical Practice",
	"industry:139" => "Mental Health Care",
	"industry:71" => "Military",
	"industry:56" => "Mining &amp; Metals",
	"industry:35" => "Motion Pictures &amp; Film",
	"industry:37" => "Museums &amp; Institutions",
	"industry:115" => "Music",
	"industry:114" => "Nanotechnology",
	"industry:81" => "Newspapers",
	"industry:100" => "Non-profit Organization Management",
	"industry:57" => "Oil &amp; Energy",
	"industry:113" => "Online Media",
	"industry:123" => "Outsourcing/Offshoring",
	"industry:87" => "Package/Freight Delivery",
	"industry:146" => "Packaging &amp; Containers",
	"industry:61" => "Paper &amp; Forest Products",
	"industry:39" => "Performing Arts",
	"industry:15" => "Pharmaceuticals",
	"industry:131" => "Philanthropy",
	"industry:136" => "Photography",
	"industry:117" => "Plastics",
	"industry:107" => "Political Organization",
	"industry:67" => "Primary/Secondary Education",
	"industry:83" => "Printing",
	"industry:105" => "Professional Training &amp; Coaching",
	"industry:102" => "Program Development",
	"industry:79" => "Public Policy",
	"industry:98" => "Public Relations &amp; Communications",
	"industry:78" => "Public Safety",
	"industry:82" => "Publishing",
	"industry:62" => "Railroad Manufacture",
	"industry:64" => "Ranching",
	"industry:44" => "Real Estate",
	"industry:40" => "Recreational Facilities &amp; Services",
	"industry:89" => "Religious Institutions",
	"industry:144" => "Renewables &amp; Environment",
	"industry:70" => "Research",
	"industry:32" => "Restaurants",
	"industry:27" => "Retail",
	"industry:121" => "Security &amp; Investigations",
	"industry:7" => "Semiconductors",
	"industry:58" => "Shipbuilding",
	"industry:20" => "Sporting Goods",
	"industry:33" => "Sports",
	"industry:104" => "Staffing &amp; Recruiting",
	"industry:22" => "Supermarkets",
	"industry:8" => "Telecommunications",
	"industry:60" => "Textiles",
	"industry:130" => "Think Tanks",
	"industry:21" => "Tobacco",
	"industry:108" => "Translation &amp; Localization",
	"industry:92" => "Transportation/Trucking/Railroad",
	"industry:59" => "Utilities",
	"industry:106" => "Venture Capital &amp; Private Equity",
	"industry:16" => "Veterinary",
	"industry:93" => "Warehousing",
	"industry:133" => "Wholesale",
	"industry:142" => "Wine &amp; Spirits",
	"industry:119" => "Wireless",
	"industry:103" => "Writing &amp; Editing",
);
$count = 0;
$data = array();
$userid = $_POST['ipaddress'];

if ($_POST['type'] == 'department') {
	if (isset($json[$userid][$_POST['id']]['department']))
		$count = count($json[$userid][$_POST['id']]['department']);

	$total = ($count > ($_POST['length'] + $_POST['start'])) ? ($_POST['length'] + $_POST['start']) : $count;
	for ($i = 0; $i < $total; $i++) {
		$arr = array();
		$arr[] = '<div><img width="100%" src="images/default.jpg"/></div>';
		$arr[] = '<div contenteditable class="dUpdate update" data-id="' . $i . '" data-column="1">' . $json[$userid][$_POST['id']]['department'][$i][1] . '</div>';
		$arr[] = '<div>' . $json[$userid][$_POST['id']]['department'][$i][0] . '</div>';
		$arr[] = '<div contenteditable class="dUpdate update" data-id="' . $i . '" data-column="2">' . $json[$userid][$_POST['id']]['department'][$i][2] . '</div>';
		$data[] = $arr;
	}
} elseif ($_POST['type'] == 'question') {
	if (isset($json[$userid][$_POST['id']]['question']))
		$count = count($json[$userid][$_POST['id']]['question']);
	$total = ($count > ($_POST['length'] + $_POST['start'])) ? ($_POST['length'] + $_POST['start']) : $count;
	for ($i = 0; $i < $total; $i++) {
		$arr = array();
		$arr[] = '<div contenteditable class="qUpdate update" data-id="' . $i . '" data-column="0">' . $json[$userid][$_POST['id']]['question'][$i][0] . '</div>';
		$arr[] = '<div>Text</div>';
		$arr[] = '<div contenteditable class="qUpdate update" data-id="' . $i . '" data-column="1">' . $json[$userid][$_POST['id']]['question'][$i][1] . '</div>';
		$data[] = $arr;
	}
} elseif ($_POST['type'] == 'keywords') {
	if (isset($json[$userid][$_POST['id']]['keywords']))
		$count = count($json[$userid][$_POST['id']]['keywords']);
	$total = ($count > ($_POST['length'] + $_POST['start'])) ? ($_POST['length'] + $_POST['start']) : $count;
	for ($i = $_POST['start']; $i < $total; $i++) {
		$arr = array();
		$arr[] = '<div contenteditable class="kUpdate update" data-id="' . $i . '" data-column="0">' . implode(',', $json[$userid][$_POST['id']]['keywords'][$i][0]) . '</div>';
		$arr[] = '<div>Text</div>';
		$arr[] = '<div contenteditable class="kUpdate update" data-id="' . $i . '" data-column="1">' . $json[$userid][$_POST['id']]['keywords'][$i][1] . '</div>';
		$data[] = $arr;
	}
}
$output = array(
	"draw" => (int)$_POST["draw"],
	"recordsTotal" => $count,
	"recordsFiltered" => $count,
	"data" => $data
);

echo json_encode($output);
