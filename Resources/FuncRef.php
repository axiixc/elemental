<?php

# ErrorCodes
string EXError( string $id )

# EXBundles
string EXApplication( string $item )
string EXPackage( string $item )
string EXLibrary( string $item )

# EXConf
string EXConfRead( string $key )
string EXConfWrite( string $key, string $value )
string EXConfDelete( string $key )
string EXConfCustomRead( string $key )
string EXConfCustomWrite( string $key, string $value )
string EXConfCustomDelete( string $key )

# EXSystem
string EXSystemUI( [ string $ui ] )
string EXSystemApp( void )
bool EXDebugMode( void )
bool EXDevMode( void )
void EXLogEnable( bool $i ) ?Deprecated
void EXLog( string $msg ) ?Deprecated

# Filesystem
array FSDirRead( string $string, [ bool $full, string $keyformat ] )
bool FSDirMake( string $string )
bool FSRename( string $string, string $rename ) 
bool FSMove( string $string, string $to )
bool FSCopy( string $string, string $to )
bool FSDelete( string $string )
bool FSPermissions( string $string, string $permissions, bool $recursive ) ?Unsupported
bool FSOwner( string $string, string $owner, bool $recursive ) ?Unsupported
bool FSGroup( string $string, string $group, bool $recursive ) ?Unsupported
string FSRead( string $string, [ string $username, string $password ] )
bool FSEdit( string $string, string $contents, string $mode )
bool FSWrite( string $string, string $contents )
bool FSAppend( string $string, string $contents )
bool FSMake( string $string )

# MySQL
resource EXMySQLQuery( string $sql )
resource EXMySQLQuery( string $sql, [ mixed $args ] )
bool EXMySQLSetConf( string $user, string $pass, string $host, string $base, string $prefix )

# UAChecks
bool UARequireType( string $args )
bool UARequireRole( string $args )
bool UAVerification( [ bool $set ] )
mixed UAType( [ string $... ] )
mixed UARole( [ string $... ] )
mixed UARoleWithName( [ string $args, [ string $... ]] )
mixed UARoleWithID( [ string $args, [ string $... ]] )
mixed UALoadState( [ string $switch ] )
void UAKilNow( string $reason )
bool UAGuest( void )

# UIContent
void UIContentAdd( string $content) -> void UIAdd( string $str )
string UIContentRead( void )
void UIContent( void )
void/bool UIDirectvoid( bool $active )

# UIJavascript
void UIJavascriptAdd( string $code )
void UIJavascriptInclude( string $file )
void UIJavascriptOnloadAdd( string $onload )
void UIJavascript( void )
void UIJavascriptOnload( void )

# UIInterface
string UICustomInterface( string $interface )
string UIDefaultInterface( void )
string UIInterface( void )

# UIMenu
mixed UIMenu( integer $id, [ bool $array, bool $return ] )
void UISubmenuReset( void )
void UISubmenuUnset( string $args )
void UISubmenuFixedWidth( integer $width, string $args, [ string $... ] )
void UISubmenuAppend( string $contents, string $position )
void UISubmenu( void )

# UIMeta
mixed UIMetaTitle( [ bool $return ] )
mixed UIMetaTagline( [ bool $return ] )
mixed UIMetaFooter( [ bool $return ] )
mixed UIMetaFaviconPath( [ bool $return ] )
void UIMetaFaviconHTML( void )
mixed UIMetaHeadTitle( [ string $title, bool $return ] )
void UIMetaBlock( void )
void UIMetaHeadBlock( void )

# UINotifications
void UINotificationAdd( string $type, string $message )
integer UINotificationCount( string $type )
void UINotification( string $type )

# UISidebar
integer UISidebarWrite( [ string $title, string $content, string $col, integer $id ] )
bool UISidebar( string $col )

# Utilities
bool EXInString( string $pointer, string $input, [ bool $case , bool $word ] )
string EXPathSafe( string $path, [ bool $strict ] )
string EXWordsSubStr( string $str, integer $length )
string EXConvertDate( string $date, string $format )
string EXHTMLSafe( string $str )
string EXJSSafe( string $str )
string EXMySQLSafe( string $str )
string EXContentType( string $input )
string filename( string $str )

# GoogleAnalytics.pk
void EXGoogleAnalytics( void )