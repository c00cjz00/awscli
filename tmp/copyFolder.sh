 #!/bin/bash
   IFS=$'\n'
   while read LINE; do
     OBJECT=`echo $LINE | cut -d ' ' -f 5`
     aws s3 mv s3://bucket/$OBJECT s3://bucket/folder/
   done < objects.txt
