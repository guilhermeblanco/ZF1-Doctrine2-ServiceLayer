#
# THIS FILE SHOULD ONLY BE USED FOR TESTING PURPOSES.
#
# IF YOU WANNA EXECUTE THE UNIT TESTS OF THIS INTEGRATION TOOL, IT IS REQUIRED
# TO CHECKOUT THE DEPENDENCIES IN ORDER TO RUN CORRECTLY THE UNIT TESTS.
#

APP_ROOT="../"

# Removing directories
rm -Rf ${APP_ROOT}library/ZF1-D2
rm -Rf ${APP_ROOT}library/Doctrine
rm -Rf ${APP_ROOT}library/Symfony
rm -Rf ${APP_ROOT}library/Zend

rm -Rf ${APP_ROOT}vendor

# Creating base directories
mkdir ${APP_ROOT}vendor

# Checking out vendor resources
git clone git://github.com/guilhermeblanco/ZendFramework1-Doctrine2.git ${APP_ROOT}vendor/ZF1-D2
git clone git://github.com/doctrine/common.git ${APP_ROOT}vendor/doctrine-common
git clone git://github.com/doctrine/dbal.git ${APP_ROOT}vendor/doctrine-dbal
git clone git://github.com/doctrine/doctrine2.git ${APP_ROOT}vendor/doctrine-orm

svn co http://framework.zend.com/svn/framework/standard/trunk/library/Zend ${APP_ROOT}vendor/zend

# Copying files
cp -R ${APP_ROOT}vendor/ZF1-D2/library/Core ${APP_ROOT}library/Core

mkdir -p ${APP_ROOT}library/Doctrine

cp -R ${APP_ROOT}vendor/doctrine-common/lib/Doctrine/Common ${APP_ROOT}library/Doctrine/Common
cp -R ${APP_ROOT}vendor/doctrine-dbal/lib/Doctrine/DBAL ${APP_ROOT}library/Doctrine/DBAL
cp -R ${APP_ROOT}vendor/doctrine-orm/lib/Doctrine/ORM ${APP_ROOT}library/Doctrine/ORM

cp -R ${APP_ROOT}vendor/doctrine-orm/lib/vendor/Symfony ${APP_ROOT}library/Symfony

svn export ${APP_ROOT}vendor/zend ${APP_ROOT}library/Zend