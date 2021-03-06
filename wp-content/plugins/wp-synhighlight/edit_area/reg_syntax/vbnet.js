editAreaLoader.load_syntax["vbnet"] = {
    'COMMENT_SINGLE' : {1: "\'"}, 
    'COMMENT_MULTI' : {}, 
    'QUOTEMARKS' : {0: "\""}, 
    'KEYWORDS' : {
        'keywordgroup1': ["3DDKSHADOW", "3DHIGHLIGHT", "3DLIGHT", "ABORT", "ABORTRETRYIGNORE", "ACTIVEBORDER", "ACTIVETITLEBAR", "ALIAS", "APPLICATIONMODAL", "APPLICATIONWORKSPACE", "ARCHIVE", "BACK", "BINARYCOMPARE", "BLACK", "BLUE", "BUTTONFACE", "BUTTONSHADOW", "BUTTONTEXT", "CANCEL", "CDROM", "CR", "CRITICAL", "CRLF", "CYAN", "DEFAULT", "DEFAULTBUTTON1", "DEFAULTBUTTON2", "DEFAULTBUTTON3", "DESKTOP", "DIRECTORY", "EXCLAMATION", "FALSE", "FIXED", "FORAPPENDING", "FORMFEED", "FORREADING", "FORWRITING", "FROMUNICODE", "GRAYTEXT", "GREEN", "HIDDEN", "HIDE", "HIGHLIGHT", "HIGHLIGHTTEXT", "HIRAGANA", "IGNORE", "INACTIVEBORDER", "INACTIVECAPTIONTEXT", "INACTIVETITLEBAR", "INFOBACKGROUND", "INFORMATION", "INFOTEXT", "KATAKANALF", "LOWERCASE", "MAGENTA", "MAXIMIZEDFOCUS", "MENUBAR", "MENUTEXT", "METHOD", "MINIMIZEDFOCUS", "MINIMIZEDNOFOCUS", "MSGBOXRIGHT", "MSGBOXRTLREADING", "MSGBOXSETFOREGROUND", "NARROW", "NEWLINE", "NO", "NORMAL", "NORMALFOCUS", "NORMALNOFOCUS", "NULLSTRING", "OBJECTERROR", "OK", "OKCANCEL", "OKONLY", "PROPERCASE", "QUESTION", "RAMDISK", "READONLY", "RED", "REMOTE", "REMOVABLE", "RETRY", "RETRYCANCEL", "SCROLLBARS", "SYSTEMFOLDER", "SYSTEMMODAL", "TEMPORARYFOLDER", "TEXTCOMPARE", "TITLEBARTEXT", "TRUE", "UNICODE", "UNKNOWN", "UPPERCASE", "VERTICALTAB", "VOLUME", "WHITE", "WIDE", "WIN16", "WIN32", "WINDOWBACKGROUND", "WINDOWFRAME", "WINDOWSFOLDER", "WINDOWTEXT", "YELLOW", "YES", "YESNO", "YESNOCANCEL"],
        'keywordgroup2': ["AndAlso", "As", "ADDHANDLER", "ASSEMBLY", "AUTO", "Binary", "ByRef", "ByVal", "BEGINEPILOGUE", "Else", "ElseIf", "Empty", "Error", "ENDPROLOGUE", "EXTERNALSOURCE", "ENVIRON", "For", "Friend", "GET", "HANDLES", "Input", "Is", "IsNot", "Len", "Lock", "Me", "Mid", "MUSTINHERIT", "MustOverride", "MYBASE", "MYCLASS", "New", "Next", "Nothing", "Null", "NOTINHERITABLE", "NOTOVERRIDABLE", "OFF", "On", "Option", "Optional", "Overloads", "OVERRIDABLE", "Overrides", "ParamArray", "Print", "Private", "Property", "Public", "Resume", "Return", "Seek", "Static", "Step", "String", "SHELL", "SENDKEYS", "SET", "Shared", "Then", "Time", "To", "THROW", "WithEvents"],
        'keywordgroup3': ["COLLECTION", "DEBUG", "DICTIONARY", "DRIVE", "DRIVES", "ERR", "FILE", "FILES", "FILESYSTEMOBJECT", "FOLDER", "FOLDERS", "TEXTSTREAM"],
        'keywordgroup4': ["BOOLEAN", "BYTE", "DATE", "DECIMIAL", "DOUBLE", "INTEGER", "LONG", "OBJECT", "SINGLE STRING"],
        'keywordgroup5': ["ADDRESSOF", "AND", "BITAND", "BITNOT", "BITOR", "BITXOR", "GETTYPE", "LIKE", "MOD", "NOT", "ORXOR"],
        'keywordgroup6': ["APPACTIVATE", "BEEP", "CALL", "CHDIR", "CHDRIVE", "CLASS", "CASE", "CATCH", "CONST", "DECLARE", "DELEGATE", "DELETESETTING", "DIM", "DO", "DOEVENTS", "END", "ENUM", "EVENT", "EXIT", "EACH", "FUNCTION", "FINALLY", "IF", "IMPORTS", "INHERITS", "INTERFACE", "IMPLEMENTS", "KILL", "LOOP", "NAMESPACE", "OPEN", "PUT", "RAISEEVENT", "RANDOMIZE", "REDIM", "REM", "RESET", "SAVESETTING", "SELECT", "SETATTR", "STOP", "SUB", "SYNCLOCK", "STRUCTURE", "SHADOWS", "SWITCH", "TRY", "WIDTH", "WITH", "WRITE", "WHILE"],
        'keywordgroup7': ["ABS", "ARRAY", "ASC", "ASCB", "ASCW", "CALLBYNAME", "CBOOL", "CBYTE", "CCHAR", "CCHR", "CDATE", "CDBL", "CDEC", "CHOOSE", "CHR", "CHR$", "CHRB", "CHRB$", "CHRW", "CINT", "CLNG", "CLNG8", "CLOSE", "COBJ", "COMMAND", "COMMAND$", "CONVERSION", "COS", "CREATEOBJECT", "CSHORT", "CSTR", "CURDIR", "CTYPE", "CVDATE", "DATEADD", "DATEDIFF", "DATEPART", "DATESERIAL", "DATEVALUE", "DAY", "DDB", "DIR", "DIR$", "EOF", "ERROR$", "EXP", "FILEATTR", "FILECOPY", "FILEDATATIME", "FILELEN", "FILTER", "FIX", "FORMAT", "FORMAT$", "FORMATCURRENCY", "FORMATDATETIME", "FORMATNUMBER", "FORMATPERCENT", "FREEFILE", "FV", "GETALLSETTINGS", "GETATTRGETOBJECT", "GETSETTING", "HEX", "HEX$", "HOUR", "IIF", "IMESTATUS", "INPUT$", "INPUTB", "INPUTB$", "INPUTBOX", "INSTR", "INSTRB", "INSTRREV", "INT", "IPMT", "IRR", "ISARRAY", "ISDATE", "ISEMPTY", "ISERROR", "ISNULL", "ISNUMERIC", "ISOBJECT", "JOIN", "LBOUND", "LCASE", "LCASE$", "LEFT", "LEFT$", "LEFTB", "LEFTB$", "LENB", "LINEINPUT", "LOC", "LOF", "LOG", "LTRIM", "LTRIM$", "MID$", "MIDB", "MIDB$", "MINUTE", "MIRR", "MKDIR", "MONTH", "MONTHNAME", "MSGBOX", "NOW", "NPER", "NPV", "OCT", "OCT$", "PARTITION", "PMT", "PPMT", "PV", "RATE", "REPLACE", "RIGHT", "RIGHT$", "RIGHTB", "RIGHTB$", "RMDIR", "RND", "RTRIM", "RTRIM$", "SECOND", "SIN", "SLN", "SPACE", "SPACE$", "SPC", "SPLIT", "SQRT", "STR", "STR$", "STRCOMP", "STRCONV", "STRING$", "STRREVERSE", "SYD", "TAB", "TAN", "TIMEOFDAY", "TIMER", "TIMESERIAL", "TIMEVALUE", "TODAY", "TRIM", "TRIM$", "TYPENAME", "UBOUND", "UCASE", "UCASE$", "VAL", "WEEKDAY", "WEEKDAYNAME", "YEAR"],
        'keywordgroup8': ["ANY", "ATN", "CALENDAR", "CIRCLE", "CURRENCY", "DEFBOOL", "DEFBYTE", "DEFCUR", "DEFDATE", "DEFDBL", "DEFDEC", "DEFINT", "DEFLNG", "DEFOBJ", "DEFSNG", "DEFSTR", "DEFVAR", "EQV", "GOSUB", "IMP", "INITIALIZE", "ISMISSING", "LET", "LINE", "LSET", "RSET", "SGN", "SQR", "TERMINATE", "VARIANT", "VARTYPE", "WEND"]
}, 
    'OPERATORS' : ["&", "&=", "*", "*=", "+", "+=", "-", "-=", "//", "/", "/=", "=", "\\", "\\=", "^", "^="], 
    'DELIMITERS' : [ '(', ')', '[', ']', '{', '}' ], 
    'STYLES' : { 
        'COMMENTS' : 'color: #008080;', 
        'QUOTESMARKS' : 'color: #808080;', 
        'KEYWORDS' : { 
        'keywordgroup1': 'color: #0600FF;',
        'keywordgroup2': 'color: #FF8000;',
        'keywordgroup3': 'color: #008000;',
        'keywordgroup4': 'color: #FF0000;',
        'keywordgroup5': 'color: #804040;',
        'keywordgroup6': 'color: #0600FF;',
        'keywordgroup7': 'color: #0600FF;',
        'keywordgroup8': 'color: #0600FF;'    }, 
       'OPERATORS' : 'color: #008000;', 
        'DELIMITERS' : 'color: #008000;' 
    } 
}; 
