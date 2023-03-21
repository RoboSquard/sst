var hash = [
  ["0", "G"],
  ["1", "v"],
  ["2", "E"],
  ["3", "t"],
  ["4", "q"],
  ["5", "U"],
  ["6", "j"],
  ["7", "a"],
  ["8", "P"],
  ["9", "L"],
  [".", "m"],
  ["#", "Z"],
];
// FFFFFFFFXX: FF-0~255 hex, XX-hash projectID
function makeKeyFromIPAndID(ip, projectid) {
  // return encrypt(ip + "#" + projectid); //origin
  var encryptedIP = "";
  for (var x in ip.split(".")) {
    var char = Number(ip.split(".")[x]).toString(16);
    if (char.length == 1) char = "0" + char;
    encryptedIP += char;
  }
  var encryptedProjectid = encrypt(projectid);
  return encryptedIP + encryptedProjectid;
}

function getInfoFromKey(key) {
  // return [decrypt(key).split("#")[0], decrypt(key).split("#")[1]]; //origin
  var encryptedIP = key.substring(0, 8);
  var encryptedProjectid = key.substring(8);

  var ip =
    parseInt(key.substring(0, 2), 16) +
    "." +
    parseInt(key.substring(2, 4), 16) +
    "." +
    parseInt(key.substring(4, 6), 16) +
    "." +
    parseInt(key.substring(6, 8), 16);

  var projectid = decrypt(encryptedProjectid);
  return [ip, projectid];
}

function encrypt(string) {
  var result = String(string);
  for (var x in hash) {
    result = result.replaceAll(hash[x][0], hash[x][1]);
  }
  return result;
}

function decrypt(string) {
  var result = string;
  for (var x in hash) {
    result = result.replaceAll(hash[x][1], hash[x][0]);
  }
  return result;
}

function makeRandomString(length) {
  var result = "";
  var characters =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  var charactersLength = characters.length;
  for (var i = 0; i < length; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
}
