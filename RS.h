#ifndef RS_H_INCLUDED
#define RS_H_INCLUDED
#include <stdlib.h>
#include <string.h>
#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <fcntl.h>
#include <termios.h>
#include <pthread.h>

#define LG_BUFFER 1024

class Serial{
    private :

        //void setspeed(speed_t vitesse);
        int nb_read ;
        unsigned char buffer[LG_BUFFER] ;
        struct termios tio ;
        int tty_fd ;
        const char* acm = "/dev/ttyS0";
        speed_t vitesse = 9600;

    public :

        Serial();
        void setBaud(speed_t vitesse);
        void setspeed(speed_t vitesse);
        void putchar(char *c, int n);
        void readSerial();
        void fclose();
};

#endif // RS_H_INCLUDED
