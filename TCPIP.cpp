#include <iostream>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <unistd.h>
#include <stdio.h>
#include <stdlib.h>
#include <errno.h>
#include <string.h>
#include <unistd.h>
#include "RS.h"

//définition additionnelle pour un système Linux
//cela revient à déclarer une constante
#define INVALID_SOCKET -1
#define SOCKET_ERROR -1
//définition des typedef
//permet de réécrire une déclaration c'est une sorte d'alias.
// ainsi on utilise SOCKADDR_IN plutôt que de déclarer une structure sockaddr_in c'est un raccourci
typedef int SOCKET;
typedef struct sockaddr_in SOCKADDR_IN;
typedef struct sockaddr SOCKADDR;
using namespace std;
int main()
{


cout << "Serveur TCP On!";


SOCKET sock;


sock = socket(AF_INET, SOCK_STREAM, 0);

fprintf(stderr, "socket() message: %s\n", strerror(errno));

SOCKADDR_IN InfoServer;


InfoServer.sin_addr.s_addr = htonl(INADDR_ANY);

InfoServer.sin_family = AF_INET;

InfoServer.sin_port = htons(9213);
printf("Demarrage du serveur sur le port N° %d .\n", 9213);
int error_message;

error_message = bind(sock, (SOCKADDR*)&InfoServer, sizeof(InfoServer));

if(error_message == 0)
{
    fprintf(stderr, "Bind message erreur : %s\n", strerror(errno));
}

listen(sock,3);
char Messbuffer[1500];
memset (Messbuffer,'\0',1500);
sockaddr_in _from;
socklen_t fromlen = sizeof(_from);
Serial port;
while(true)
{   // boucle d'écoute tcp/IP
    SOCKET client = accept(sock, NULL, NULL);
    error_message = recv(client, Messbuffer, 1500, 0);
    /*if(error_message<1){
    fprintf(stderr, "recvFrom message: %s\n", strerror(errno));
    }else{*/
    fprintf(stderr,"Client : IP : %s ", inet_ntoa(_from.sin_addr));
    fprintf(stderr," Port : %d ", ntohs(_from.sin_port));
    fprintf(stderr," Message Reçu : %s ", Messbuffer);
    //réponse du serveur
    //A la place de "suis serveur" vous pouvez envoyer l'information que vous souhaitez

    char buffer[]="Bien Recu";

    int octet_message = send(client,buffer,sizeof(buffer),0);
    if(octet_message == 0)
    {
        fprintf(stderr, "sendto message erreur : %s\n", strerror(errno));
    }
    fprintf(stderr,"\n");
    //envoie des trame corespondant a la reception
    if (strcmp(Messbuffer,"dh") == 0) //deplacement haut
    {
        char texte[9];
        texte[0] = 0x81;
        texte[1] = 0x01;
        texte[2] = 0x06;
        texte[3] = 0x01;
        texte[4] = 0x03;
        texte[5] = 0x01;
        texte[6] = 0x03;
        texte[7] = 0x01;
        texte[8] = 0xFF;
        port.putchar(texte, 9);
        printf("appele messbuffer dh");
    }

    if (strcmp(Messbuffer,"dg") == 0) //deplacement gauche
    {
        char texte[9];
        texte[0] = 0x81;
        texte[1] = 0x01;
        texte[2] = 0x06;
        texte[3] = 0x01;
        texte[4] = 0x03;
        texte[5] = 0x01;
        texte[6] = 0x01;
        texte[7] = 0x03;
        texte[8] = 0xFF;
        port.putchar(texte, 9);
    }

    if (strcmp(Messbuffer,"ds") == 0) //deplacement stop
    {
        char texte[9];
        texte[0] = 0x81;
        texte[1] = 0x01;
        texte[2] = 0x06;
        texte[3] = 0x01;
        texte[4] = 0x03;
        texte[5] = 0x01;
        texte[6] = 0x03;
        texte[7] = 0x03;
        texte[8] = 0xFF;
        port.putchar(texte, 9);
    }

    if (strcmp(Messbuffer,"dd") == 0) //deplacement droite
    {
        char texte[9];
        texte[0] = 0x81;
        texte[1] = 0x01;
        texte[2] = 0x06;
        texte[3] = 0x01;
        texte[4] = 0x03;
        texte[5] = 0x01;
        texte[6] = 0x02;
        texte[7] = 0x03;
        texte[8] = 0xFF;
        port.putchar(texte, 9);
    }

    if (strcmp(Messbuffer,"db") == 0) //deplacement bas
    {
        char texte[9];
        texte[0] = 0x81;
        texte[1] = 0x01;
        texte[2] = 0x06;
        texte[3] = 0x01;
        texte[4] = 0x03;
        texte[5] = 0x01;
        texte[6] = 0x03;
        texte[7] = 0x02;
        texte[8] = 0xFF;
        port.putchar(texte, 9);
    }

    if (strcmp(Messbuffer,"zp") == 0) //zoom plus
    {
        char texte[6];
        texte[0] = 0x81;
        texte[1] = 0x01;
        texte[2] = 0x04;
        texte[3] = 0x07;
        texte[4] = 0x02;
        texte[5] = 0xFF;

        port.putchar(texte, 6);
    }

    if (strcmp(Messbuffer,"zs") == 0) //zoom stop
    {
        char texte[6];
        texte[0] = 0x81;
        texte[1] = 0x01;
        texte[2] = 0x04;
        texte[3] = 0x07;
        texte[4] = 0x00;
        texte[5] = 0xFF;

        port.putchar(texte, 6);
    }

    if (strcmp(Messbuffer,"zm") == 0) //zoom moins
    {
        char texte[6];
        texte[0] = 0x81;
        texte[1] = 0x01;
        texte[2] = 0x04;
        texte[3] = 0x07;
        texte[4] = 0x03;
        texte[5] = 0xFF;

        port.putchar(texte, 6);
    }

    if (strcmp(Messbuffer,"ba") == 0) //trame balayage automatique
    {
        char home[5];
        home[0] = 0x81;
        home[1] = 0x01;
        home[2] = 0x06;
        home[3] = 0x04;
        home[4] = 0xFF;

        char gauche[9];
        gauche[0] = 0x81;
        gauche[1] = 0x01;
        gauche[2] = 0x06;
        gauche[3] = 0x01;
        gauche[4] = 0x07;
        gauche[5] = 0x05;
        gauche[6] = 0x01;
        gauche[7] = 0x03;
        gauche[8] = 0xFF;

        char droite[9];
        droite[0] = 0x81;
        droite[1] = 0x01;
        droite[2] = 0x06;
        droite[3] = 0x01;
        droite[4] = 0x07;
        droite[5] = 0x05;
        droite[6] = 0x02;
        droite[7] = 0x03;
        droite[8] = 0xFF;

        for(int i = 0; i<3 ;i++)
        {
            port.putchar(gauche, 9);
            sleep (7);
            port.putchar(droite, 9);
            sleep (7);
        }
        sleep (3);
        port.putchar(home, 5);
    }

    if (strcmp(Messbuffer,"ho") == 0)
    {
        char texte[5];
        texte[0] = 0x81;
        texte[1] = 0x01;
        texte[2] = 0x06;
        texte[3] = 0x04;
        texte[4] = 0xFF;
        port.putchar(texte, 5);
    }

    if (strcmp(Messbuffer,"on") == 0)
    {
        char texte[6];
        texte[0] = 0x81;
        texte[1] = 0x01;
        texte[2] = 0x04;
        texte[3] = 0x00;
        texte[4] = 0x02;
        texte[5] =  0xFF;
        port.putchar(texte, 6);
    }

    if (strcmp(Messbuffer,"of") == 0)
    {
        char texte[6];
        texte[0] = 0x81;
        texte[1] = 0x01;
        texte[2] = 0x04;
        texte[3] = 0x00;
        texte[4] = 0x03;
        texte[5] =  0xFF;
        port.putchar(texte, 6);
    }



    //}
    close(client);
}
close(sock);
port.fclose();


return 0;
}
