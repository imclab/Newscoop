<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
	"http://www.w3.org/TR/REC-html40/loose.dtd">
<HTML>
<?php
include ("../../lib_campsite.php");
$globalfile=selectLanguageFile('../..','globals');
$localfile=selectLanguageFile('.','locals');
@include ($globalfile);
@include ($localfile);
include ("../../languages.php");
require_once("$DOCUMENT_ROOT/db_connect.php");
include($_SERVER['DOCUMENT_ROOT']."/classes/common.php");
require_once($_SERVER['DOCUMENT_ROOT']."/priv/CampsiteInterface.php");

todefnum('TOL_UserId');
todefnum('TOL_UserKey');
query ("SELECT * FROM Users WHERE Id=$TOL_UserId AND KeyId=$TOL_UserKey", 'Usr');
$access=($NUM_ROWS != 0);
if ($NUM_ROWS) {
	fetchRow($Usr);
	query ("SELECT * FROM UserPerm WHERE IdUser=".getVar($Usr,'Id'), 'XPerm');
	if ($NUM_ROWS){
		fetchRow($XPerm);
	} else {
		$access = 0; //added lately; a non-admin can enter the administration area;
		             // he/she exists but doesn't have ANY rights
	}
	$xpermrows= $NUM_ROWS;
} else {
	query ("SELECT * FROM UserPerm WHERE 1=0", 'XPerm');
}

if ($access) {
	query ("SELECT Publish FROM UserPerm WHERE IdUser=".getVar($Usr,'Id'), 'Perm');
	if ($NUM_ROWS) {
		fetchRow($Perm);
		$access = (getVar($Perm,'Publish') == "Y");
	}
	else $access = 0;
}
?>

<HEAD>
	<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<META HTTP-EQUIV="Expires" CONTENT="now">
	<TITLE><?php  putGS("Delete scheduled publish action"); ?></TITLE>
<?php if ($access == 0) { ?>
	<META HTTP-EQUIV="Refresh" CONTENT="0; URL=/priv/ad.php?ADReason=<?php  print encURL(getGS("You do not have the right to schedule issues or articles for automatic publishing." )); ?>">
<?php } ?>
</HEAD>

<?php if ($access) { ?>
<STYLE>
	BODY { font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 10pt; }
	SMALL { font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 8pt; }
	FORM { font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 10pt; }
	TH { font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 10pt; }
	TD { font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 10pt; }
	BLOCKQUOTE { font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 10pt; }
	UL { font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 10pt; }
	LI { font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 10pt; }
	A  { font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 10pt; text-decoration: none; color: darkblue; }
	ADDRESS { font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 8pt; }
</STYLE>
<BODY  BGCOLOR="WHITE" TEXT="BLACK" LINK="DARKBLUE" ALINK="RED" VLINK="DARKBLUE">

<?php
	todefnum('Pub');
	todefnum('Issue');
	todefnum('Language');
	todef('publish_time');
?>
<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="1" WIDTH="100%">
	<TR>
		<TD ROWSPAN="2" WIDTH="1%"><IMG SRC="/priv/img/sign_big.gif" BORDER="0"></TD>
		<TD>
		    <DIV STYLE="font-size: 12pt"><B><?php  putGS("Delete scheduled publish action"); ?></B></DIV>
		    <HR NOSHADE SIZE="1" COLOR="BLACK">
		</TD>
	</TR>
	<TR><TD ALIGN=RIGHT><TABLE BORDER="0" CELLSPACING="1" CELLPADDING="0"><TR><TD><A HREF="/priv/pub/issues/?Pub=<?php p($Pub); ?>" ><IMG SRC="/priv/img/tol.gif" BORDER="0" ALT="<?php  putGS("Issues"); ?>"></A></TD><TD><A HREF="/priv/pub/issues/?Pub=<?php p($Pub); ?>" ><B><?php  putGS("Issues");  ?></B></A></TD>
<TD><A HREF="/priv/pub/" ><IMG SRC="/priv/img/tol.gif" BORDER="0" ALT="<?php  putGS("Publications"); ?>"></A></TD><TD><A HREF="/priv/pub/" ><B><?php  putGS("Publications");  ?></B></A></TD>
<TD><A HREF="/priv/home.php" ><IMG SRC="/priv/img/tol.gif" BORDER="0" ALT="<?php  putGS("Home"); ?>"></A></TD><TD><A HREF="/priv/home.php" ><B><?php  putGS("Home");  ?></B></A></TD>
<TD><A HREF="/priv/logout.php" ><IMG SRC="/priv/img/tol.gif" BORDER="0" ALT="<?php  putGS("Logout"); ?>"></A></TD><TD><A HREF="/priv/logout.php" ><B><?php  putGS("Logout");  ?></B></A></TD>
</TR></TABLE></TD></TR>
</TABLE>

<?php
	query ("SELECT * FROM Issues WHERE IdPublication=$Pub AND Number=$Issue AND IdLanguage=$Language", 'q_iss');
	if ($NUM_ROWS) {
		query ("SELECT * FROM Publications WHERE Id=$Pub", 'q_pub');
		if ($NUM_ROWS) {
		    query ("SELECT Name FROM Languages WHERE Id=$Language", 'q_lang');
		    fetchRow($q_iss);
		    fetchRow($q_pub);
		    fetchRow($q_lang);
?><TABLE BORDER="0" CELLSPACING="1" CELLPADDING="1" WIDTH="100%"><TR>
<TD ALIGN="RIGHT" WIDTH="1%" NOWRAP VALIGN="TOP">&nbsp;<?php  putGS("Publication"); ?>:</TD><TD BGCOLOR="#D0D0B0" VALIGN="TOP"><B><?php pgetHVar($q_pub,'Name'); ?></B></TD>

<TD ALIGN="RIGHT" WIDTH="1%" NOWRAP VALIGN="TOP">&nbsp;<?php  putGS("Issue"); ?>:</TD><TD BGCOLOR="#D0D0B0" VALIGN="TOP"><B><?php pgetHVar($q_iss,'Number'); ?>. <?php pgetHVar($q_iss,'Name'); ?> (<?php pgetHVar($q_lang,'Name'); ?>)</B></TD>

</TR></TABLE>

<P>
<CENTER><TABLE BORDER="0" CELLSPACING="0" CELLPADDING="8" BGCOLOR="#C0D0FF" ALIGN="CENTER">
	<TR>
		<TD COLSPAN="2">
			<B> <?php  putGS("Delete scheduled publish action"); ?> </B>
			<HR NOSHADE SIZE="1" COLOR="BLACK">
		</TD>
	</TR>
	<TR>
		<TD COLSPAN="2"><BLOCKQUOTE>
<?php
    $AFFECTED_ROWS=0;
	$sql = "DELETE FROM IssuePublish WHERE IdPublication = $Pub AND NrIssue = $Issue AND IdLanguage = $Language AND PublishTime = '$publish_time'";
	query ($sql);
	$del = $AFFECTED_ROWS > 0;

	if ($del) { ?>		<LI><?php putGS('The action scheduled on $1 has been deleted.','<B>'.$publish_time.'</B>' ); ?></LI>
	<?php } else { ?>		<LI><?php putGS('The action scheduled on $1 could not be deleted.','<B>'.$publish_time.'</B>' ); ?></LI>
	<?php } ?>	</BLOCKQUOTE></TD>
	</TR>
	<TR>
		<TD COLSPAN="2">
		<DIV ALIGN="CENTER">
<?php
    if ($del) { ?>		<INPUT TYPE="button" NAME="Done" VALUE="<?php  putGS('Done'); ?>" ONCLICK="location.href='/priv/pub/issues/autopublish.php?Pub=<?php p($Pub); ?>&Issue=<?php p($Issue); ?>&Language=<?php p($Language); ?>'">
<?php } else { ?>		<INPUT TYPE="button" NAME="OK" VALUE="<?php  putGS('OK'); ?>" ONCLICK="location.href='/priv/pub/issues/autopublish.php?Pub=<?php p($Pub); ?>&Issue=<?php p($Issue); ?>&Language=<?php p($Language); ?>'">
<?php } ?>		</DIV>
		</TD>
	</TR>
</TABLE></CENTER>
<P>

<?php } else { ?><BLOCKQUOTE>
	<LI>No such publication.</LI>
</BLOCKQUOTE>
<?php } ?>
<?php } else { ?><BLOCKQUOTE>
	<LI>No such issue.</LI>
</BLOCKQUOTE>
<?php } ?>
<?php }
CampsiteInterface::CopyrightNotice();
?>
</BODY>

</HTML>
