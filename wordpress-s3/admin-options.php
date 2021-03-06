<?php if ( isset( $error ) && $error ):?>
<div id="message" class="error fade"><p><strong><?php echo $error?></strong></p></div>
<?php elseif ( isset( $message ) && $message ):?>
<div id="message" class="updated fade"><p><strong><?php echo $message?></strong></p></div>
<?php endif;?>
<script type="text/javascript">
function s3_selectBucket(obj) {
    if (obj.options[obj.selectedIndex].value == 'new') {
        var bucket = prompt("Bucket name: ");
        if (bucket) {
            var len = obj.options.length
            obj.options[len] = new Option("New bucket: " + bucket, bucket);
            obj.options[len].selected = true;
        }
    }
}
</script>
<style type="text/css">
div.album {
    float:left;
    width:200px;
    height:150px;
    margin-right:15px;
}
div.album td {
    font-size:0.9em;
}
div.album-hidden img {
    opacity:0.5;
}
.form-table {
	max-width: 850px;
	float: left;
	clear: none;
	margin: 0 40px 20px 0;
}
.form-table th h3 {
	margin: 0;
}
.wps3-author {
	width: 250px;
	float: left;
	padding: 20px;
	border: 1px solid #ccc;
	overflow: hidden;
	margin: 0 0 40px 0;
}
.wps3-author img {
	float: left;
	margin-right: 20px;
	border-radius: 32px;
}
.wps3-author .desc {
	float: left;
}
.wps3-author h3 {
	font-size: 12px;
	margin: 0;
}
.wps3-author h2 {
	font-size: 18px;
	margin: 0;
	padding: 0;
}
.wps3-author h2 a {
	color: #464646;
	text-decoration: none;
}
.wps3-author h2 a:hover {
	color: #000;
}
.wps3-author p {
	margin: 0;
}
.wps3-author .github {
	padding-top: 5px;
}
</style>


<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div>
<h2 id="write-post">Amazon S3 and CloudFront</h2>
<?php
	global $TanTanVersionCheck;
if ( is_object( $TanTanVersionCheck ) ):?>
<div style="width:200px; border:1px solid #ccc;padding:10px; float:right; margin:0 0 10px 10px;">
<strong>Plugin Updates:</strong><br />
<a href="plugins.php?page=tantan/version-check.php">Check for updates to this plugin &gt;</a>
</div>
<?php endif;?>

<table class="form-table">
<form method="post">
<input type="hidden" name="action" value="save" />
<tr valign="top">
<td colspan="2">
	<h3>AWS Access Credentials</h3>
	<p>
		If you don't have an Amazon S3 account yet, you need to
		<a href="http://aws.amazon.com/s3/">sign up</a>.<br />

		Once you've signed up, you can retrieve your access credentials from the
		<a href="https://portal.aws.amazon.com/gp/aws/securityCredentials">Security Credentials</a>
		page of your Amazon Web Services account.
	</p>
</td>
</tr>
<tr valign="top">
<th width="33%" scope="row">Access Key ID:</th>
<td><input type="text" name="options[key]" value="<?php echo $options['key'];?>" size="50" /></td>
</tr>
<tr valign="top">
<th width="33%" scope="row">Secret Access Key:</th>
<td><input type="text" name="options[secret]" value="<?php echo $options['secret'] ? '-- not shown --' : '';?>" size="50" /></td>
</tr>
<?php if ( !isset( $buckets ) || !is_array( $buckets ) ):?>
<tr valign="top">
<td colspan="2">
<p class="submit">
<input type="submit" class="button button-primary" value="Next Step" />
</p>
</td>
</tr>
<?php else:?>
	<tr valign="top">
	<td colspan="2">
		<h3>S3 Settings</h3>
	</td>
	</tr>
	<tr valign="top">
	<th width="33%" scope="row">&nbsp;</th>
	<td>
		<select name="options[bucket]" size="1" onchange="return s3_selectBucket(this)" style="margin-bottom: 5px; width: 380px;">
		<option value="">-- Select an S3 Bucket --</option>
		<?php if ( is_array( $buckets ) ) foreach ( $buckets as $bucket ):?>
		    <option value="<?php echo $bucket?>" <?php echo ( isset( $options['bucket'] ) && $bucket == $options['bucket'] ) ? 'selected="selected"' : ''; ?>><?php echo $bucket?></option>
		<?php endforeach;?>
		<option value="new">Create a new bucket...</option>
		</select><br />

		<input type="checkbox" name="options[virtual-host]" value="1" id="virtual-host" <?php echo ( isset( $options['virtual-host'] ) && $options['virtual-host'] ) ? 'checked="checked" ' : '';?> />
		<label for="virtual-host"> Bucket is setup for virtual hosting</label> (<a href="http://docs.amazonwebservices.com/AmazonS3/2006-03-01/VirtualHosting.html">more info</a>)
		<br />

		<input type="checkbox" name="options[expires]" value="315360000" id="expires" <?php echo ( isset( $options['expires'] ) && $options['expires'] ) ? 'checked="checked" ' : ''; ?> />
		<label for="expires"> Set a <a href="http://developer.yahoo.com/performance/rules.html#expires" target="_blank">far future HTTP expiration header</a> for uploaded files <em>(recommended)</em></label>
		<br />

		<input type="checkbox" name="options[permissions]" value="public" id="permissions" <?php echo ( isset( $options['permissions'] ) && $options['permissions'] == 'public' ) ? 'checked="checked" ' : ''; ?> />
		<label for="permissions"> Force set the permissions on all files in the bucket to public</label>

	</td>
	</tr>

	<tr valign="top">
	<td colspan="2">
		<h3>CloudFront Settings</h3>
	</td>
	</tr>
	<tr valign="top">
	<th width="33%" scope="row">Domain Name</th>
	<td>
		<input type="text" name="options[cloudfront]" value="<?php echo isset( $options['cloudfront'] ) ? $options['cloudfront'] : ''; ?>" size="50" />
		<p class="description">Leave blank if you aren't using CloudFront.</p>
	</td>
	</tr>

	<tr valign="top">
	<td colspan="2">
		<h3>Plugin Settings</h3>
	</td>
	</tr>

	<tr valign="top">
	<th width="33%" scope="row">&nbsp;</th>
	<td>
		<input type="checkbox" name="options[wp-uploads]" value="1" id="wp-uploads" <?php echo ( isset( $options['wp-uploads'] ) && $options['wp-uploads'] ) ? 'checked="checked" ' : ''; ?> />
		<label for="wp-uploads"> Enable copying of media files to S3 and serving media files from S3/CloudFront</label>
		<p class="description">Uncheck this to revert back to using your own web host for storage and delivery at anytime.</p>
	</td>
	</tr>

<tr>
<td colspan="2">
<p class="submit">
<input type="submit" class="button button-primary" value="Save Changes" />
</p>
</td>
</tr>
<?php endif;?>


</form>

</table>



<div class="wps3-author">
    <img src="http://www.gravatar.com/avatar/e538ca4cb34839d4e5e3ccf20c37c67b?s=128&amp;d" width="64" height="64" />
    <div class="desc">
        <h3>Maintained by</h3>
        <h2>Brad Touesnard</h2>
        <p>
            <a href="http://profiles.wordpress.org/bradt/">Profile</a>
            &nbsp;&nbsp;
            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=5VPMGLLK94XJC">Donate</a>
        </p>
        <p class="github">
            <a href="https://github.com/bradt/wp-tantan-s3/">Contribute on GitHub</a>
        </p>
    </div>
</div>



</div>
