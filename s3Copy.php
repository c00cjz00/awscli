<?php
# config #
$endpoint="s3-cloud.nchc.org.tw";
$sourceBucket="islide";  $copyBucket="mytmpbuckt001";
$adminID="1603014"; $userID="1703181"; $publicBucket=1;
$sourceFile_array=array("001.php","index.html");

# main #
$mainCmd="aws --profile=cloudian --endpoint-url=http://s3-cloud.nchc.org.tw";

# permission #
if ($publicBucket==1){
$myPermission="--grant-full-control id=\"".$adminID."\" \
--grant-read id=\"".$userID."\",uri=\"http://acs.amazonaws.com/groups/global/AllUsers\" \
--grant-read-acp id=\"".$userID."\",uri=\"http://acs.amazonaws.com/groups/global/AllUsers\" \
";
}else{
$myPermission="--grant-full-control id=\"".$adminID."\" \
--grant-read id=\"".$userID."\" \
--grant-read-acp id=\"".$userID."\" \
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
if ($rmKey==1) { $cmd=$mainCmd." s3 rb s3://".$copyBucket." --force"; echo $cmd."\n"; passthru($cmd); sleep(1); }

# make a bucket #
$cmd=$mainCmd." s3 mb s3://".$copyBucket; echo $cmd."\n"; passthru($cmd); sleep(1);
$cmd=$mainCmd." s3api put-bucket-acl --bucket ".$copyBucket." ".$myPermission; echo $cmd."\n"; passthru($cmd); sleep(1);


# copy index.html2bucketFile #
for($i=0;$i<count($sourceFile_array);$i++){
 $sourceFile=trim($sourceFile_array[$i]);
 $cmd=$mainCmd." s3api copy-object --copy-source ".$sourceBucket."/".$sourceFile." --key ".$sourceFile." --bucket ".$copyBucket." ".$myPermission; echo $cmd."\n"; passthru($cmd); sleep(1);
}

# Result #
$hyperLink="https://".$copyBucket.".".$endpoint."/index.html";
echo "Add External Bucket [ ".$copyBucket." ] for userid: ".$userID."\n";
echo $hyperLink."\n";






