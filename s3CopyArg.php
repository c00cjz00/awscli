<?php
## example: php s3CopyArg.php s3Copy.list

# copy file #
$copyFile=trim($argv[1]);
if (!is_file($copyFile)) { echo "no input file\n"; exit(); }
$sourceFile_array=file($copyFile);
//$sourceFile_array=array("index.html","A45W19023_S11_L001_R1_001.fastq.gz","A43W18524_S8_L001_R2_001.fastq","A45W19002_S1_L001_R1_001.fastq.gz","A45W19023_S11_L001_R2_001.fastq.gz","A43W18512_S1_L001_R1_001.fastq","A43W18512_R2.fil_pairs_R2.fastq","A43W18524_S8_L001_R1_001.fastq","A44W18534_S10_L001_R1_001.fastq.gz","A43W18512_R1.fil_pairs_R1.fastq","A43W18512_S1_L001_R2_001.fastq","A45W19002_S1_L001_R2_001.fastq.gz");


# admin config #
date_default_timezone_set("Asia/Taipei"); 
$today = "hmpsymlink".date("YmdHis"); 
$endpoint="s3-cloud.nchc.org.tw";  
$adminID="1603014"; 
$sourceBucket="hmpportal"; 
$publicBucket=1;

# user config #
$userID_array=array("u00cjz00","1703181");  
$copyBucket="hmpbuckt".$userID_array[0]; 
$uid=10183; $gid=3254;

# main cmd #
$mainCmd="aws --profile=cloudian --endpoint-url=http://s3-cloud.nchc.org.tw";

# permission #
$userIDList="id=\"".implode("\",id=\"",$userID_array)."\"";
if ($publicBucket==1){
$myPermission="--grant-full-control id=\"".$adminID."\" \
--grant-read \"".$userIDList."\",uri=\"http://acs.amazonaws.com/groups/global/AllUsers\" \
--grant-read-acp \"".$userIDList."\",uri=\"http://acs.amazonaws.com/groups/global/AllUsers\" \
";
}else{
$myPermission="--grant-full-control id=\"".$adminID."\" \
--grant-read \"".$userIDList."\" \
--grant-read-acp \"".$userIDList."\" \
";
}

# ls bucket #
$rmKey=0;
$cmd=$mainCmd." s3 ls "; echo $cmd."\n"; $result=shell_exec($cmd); $tmpArr=explode("\n",$result);
for($i=0;$i<count($tmpArr);$i++){
 $tmp=trim($tmpArr[$i]); $smp=explode(" ",$tmp,3);
 if ((count($smp)==3) && ($smp[2]==$copyBucket)) { $rmKey=1; break; }
}

# remove bucket #
//if ($rmKey==1) { $cmd=$mainCmd." s3 rb s3://".$copyBucket." --force"; echo $cmd."\n"; passthru($cmd); }

# make a bucket #
if ($rmKey==0) { 
$cmd=$mainCmd." s3 mb s3://".$copyBucket; echo $cmd."\n"; passthru($cmd); 
$cmd=$mainCmd." s3api put-bucket-acl --bucket ".$copyBucket." ".$myPermission; echo $cmd."\n"; passthru($cmd); 
}

# copy index.html2bucketFile #
for($i=0;$i<count($sourceFile_array);$i++){
 $sourceFile=trim($sourceFile_array[$i]);
 if ($sourceFile!=""){
  $cmd=$mainCmd." s3api copy-object --copy-source ".$sourceBucket."/".$sourceFile." --key ".$today."/".$sourceFile." --bucket ".$copyBucket." ".$myPermission; echo $cmd."\n"; passthru($cmd); 
 }
}

# 01. Result #
echo "01. Add External Bucket [ ".$copyBucket." ], Folder [ ".$today." ] for userid: ".$userIDList."\n\n";

# 02. s3 sync #
$externalCmd="aws --profile=".$userID_array[0]." --endpoint-url=http://s3-cloud.nchc.org.tw";
$s3Sync=$externalCmd." s3 sync s3://".$copyBucket."/".$today." ".$today; 
echo "02. s3 sync command\n";
echo $s3Sync."\n\n";

# 03. s3fs mount folder #
if (isset($uid) && isset($gid) && ($uid=!"") && ($gid!="")) {
 $s3fsMountFolder="/work1/".$copyBucket;
 $s3fsLink="fuermount -u ".$s3fsMountFolder."\nmkdir -p ".$s3fsMountFolder."\n./s3fs ".$copyBucket." ".$s3fsMountFolder." -o url=http://".$endpoint." -o use_path_request_style -o uid=10183,gid=3254,umask=000";
 echo "03. Add s3fs mount command\n";
 echo "Edit  ~/.passwd-s3fs\n";
 echo $s3fsLink."\n\n";
}

# 04. Hyper Link #
if ($publicBucket==1) {
 $hyperLink="https://".$copyBucket.".".$endpoint."/".$today."/index.html?prefix=".$today."/";
 echo "04. ".$hyperLink."\n\n";
}





############## install package ################
/*
https://docs.aws.amazon.com/cli/latest/userguide/install-bundle.html#install-bundle-user 
Install the AWS CLI without Sudo (Linux, macOS, or Unix)
If you don't have sudo permissions or want to install the AWS CLI only for the current user, you can use a modified version of the previous commands.

$ curl "https://s3.amazonaws.com/aws-cli/awscli-bundle.zip" -o "awscli-bundle.zip"
$ unzip awscli-bundle.zip
$ ./awscli-bundle/install -b ~/bin/aws
This installs the AWS CLI to the default location (~/.local/lib/aws) and creates a symbolic link (symlink) at ~/bin/aws. Make sure that ~/bin is in your PATH environment variable for the symlink to work.

$ echo $PATH | grep ~/bin     // See if $PATH contains ~/bin (output will be empty if it doesn't)
$ export PATH=~/bin:$PATH     // Add ~/bin to $PATH if necessary
Tip

To ensure that your $PATH settings are retained between sessions, add the export line to your shell profile (~/.profile, ~/.bash_profile, and so on).
*/

/*
https://cloudian.com/blog/aws-cli-s3-compatible-storage/
aws configure
vi ./aws/configure
[cloudian]
region = s3-cloud.nchc.org.tw
output = json
[u00cjz00]
region = s3-cloud.nchc.org.tw
output = json

vi ./aws/credentials
[cloudian]
aws_access_key_id = xxx
aws_secret_access_key = xxx
[u00cjz00]
aws_access_key_id = xxx
aws_secret_access_key = xxx[
*/






