dnl $Id$
dnl config.m4 for extension hellotom

dnl Comments in this file start with the string 'dnl'.
dnl Remove where necessary. This file will not work
dnl without editing.

dnl If your extension references something external, use with:

dnl PHP_ARG_WITH(hellotom, for hellotom support,
dnl Make sure that the comment is aligned:
dnl [  --with-hellotom             Include hellotom support])

dnl Otherwise use enable:

PHP_ARG_ENABLE(hellotom, whether to enable hellotom support,
[  --enable-hellotom           Enable hellotom support])

if test "$PHP_HELLOTOM" != "no"; then
  dnl Write more examples of tests here...

  dnl # --with-hellotom -> check with-path
  dnl SEARCH_PATH="/usr/local /usr"     # you might want to change this
  dnl SEARCH_FOR="/include/hellotom.h"  # you most likely want to change this
  dnl if test -r $PHP_HELLOTOM/$SEARCH_FOR; then # path given as parameter
  dnl   HELLOTOM_DIR=$PHP_HELLOTOM
  dnl else # search default path list
  dnl   AC_MSG_CHECKING([for hellotom files in default path])
  dnl   for i in $SEARCH_PATH ; do
  dnl     if test -r $i/$SEARCH_FOR; then
  dnl       HELLOTOM_DIR=$i
  dnl       AC_MSG_RESULT(found in $i)
  dnl     fi
  dnl   done
  dnl fi
  dnl
  dnl if test -z "$HELLOTOM_DIR"; then
  dnl   AC_MSG_RESULT([not found])
  dnl   AC_MSG_ERROR([Please reinstall the hellotom distribution])
  dnl fi

  dnl # --with-hellotom -> add include path
  dnl PHP_ADD_INCLUDE($HELLOTOM_DIR/include)

  dnl # --with-hellotom -> check for lib and symbol presence
  dnl LIBNAME=hellotom # you may want to change this
  dnl LIBSYMBOL=hellotom # you most likely want to change this 

  dnl PHP_CHECK_LIBRARY($LIBNAME,$LIBSYMBOL,
  dnl [
  dnl   PHP_ADD_LIBRARY_WITH_PATH($LIBNAME, $HELLOTOM_DIR/lib, HELLOTOM_SHARED_LIBADD)
  dnl   AC_DEFINE(HAVE_HELLOTOMLIB,1,[ ])
  dnl ],[
  dnl   AC_MSG_ERROR([wrong hellotom lib version or lib not found])
  dnl ],[
  dnl   -L$HELLOTOM_DIR/lib -lm
  dnl ])
  dnl
  dnl PHP_SUBST(HELLOTOM_SHARED_LIBADD)

  PHP_NEW_EXTENSION(hellotom, hellotom.c, $ext_shared)
fi
