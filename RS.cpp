#include "RS.h"

using namespace std ;

void Serial::setspeed(speed_t vitesse)
    {
        cfsetospeed(&tio, vitesse) ;
        cfsetispeed(&tio, vitesse) ;
        tcsetattr(tty_fd,TCSANOW,&tio) ;
    }

Serial::Serial()
    {
        memset(&tio,0,sizeof(tio)) ;
        tio.c_iflag=0 ; //IGNPAR ;
        tio.c_oflag=0 ;
        tio.c_cflag=CS8|CREAD|CLOCAL ; // 8n1, see termios.h for more information
        tio.c_lflag=0 ;
        tio.c_cc[VMIN]=1 ;
        tio.c_cc[VTIME]=5 ;

        tty_fd = open(acm, O_RDWR) ;
        if(tty_fd < 0)
        {
            perror("open") ;
            printf("file => %s\n", acm) ;
            exit(EXIT_FAILURE);
        }
    }

void Serial::setBaud(speed_t vitesse)
    {
        if (vitesse < 2401)
        setspeed(B2400) ;
        else if (vitesse < 4801)
        setspeed(B4800) ;
        else if (vitesse < 9601)
        setspeed(B9600) ;
    }

void Serial::putchar(char *c, int n)
    {
        write(tty_fd,c,n) ;
        printf("dans le putchar %s", c);
    }

void Serial::readSerial()
    {
        nb_read = read(tty_fd, buffer, LG_BUFFER) ;
        write(STDOUT_FILENO, buffer, nb_read) ;
    }


void Serial::fclose()
    {
        if (close(tty_fd)<0)
        {
            perror("close") ;
            exit(EXIT_FAILURE) ;
        }
    }
