$spreadsheet_url="https://docs.google.com/spreadsheet/pub?key=2PACX-1vTERxGzP9c65waSCL3Wskg2JDFi4GkIfC62uPIKo9Drxy5L46K1JvPFudehEEFd_gzIuIam74PDbwAs&single=true&gid=0&output=csv";

if(!ini_set('default_socket_timeout', 15)) echo "
<!-- unable to change socket timeout -->";

if (($handle = fopen($spreadsheet_url, "r")) !== FALSE) {
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
$spreadsheet_data[] = $data;
}
fclose($handle);
}
else
die("Problem reading csv");