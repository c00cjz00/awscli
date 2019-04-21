# awscli
############## install package ################

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

# awscli
