# PHP Code protect 

This simple php script encrypts and packages any php script into a c header file, which then can be included into your project and executed from a C program. 

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

PHP version should be installed and must be accessible from terminal
C Program should be compiled with g++
C Program should be compiled with -std=c++11

### Installing
Download the code 
Run -   php convert.php example.php prog.h
The script takes 2 parameters. 
First arg is the php script filename which needs to be encoded.
Second argument is the name of c header file which is created by the tool. 
 
There is Makefile.
Which builds a sample application. Just run make and it should create prog.h and app after encoding example.php. 


## Authors

* **Mumtaz Ahmad** - *Initial work* 
ahmad-mumtaz1@hotmail.com

## License

This project is licensed under the MIT License - see the [LICENSE.md](License.md) file for details




