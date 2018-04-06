all:
	php convert.php example.php prog.h
	g++ -std=c++11  main.cpp -o app
	
