﻿

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

 //création de socket --------------------
 //prototype de la création de socket int socket(int domain, int type, int protocol);
 //la fonction retourne un int çà tombe bien on à typedef int SOCKET de déclarer en en-tête
 // on va donc l'utiliser c'est plus sympa d'avoir a écrire SOCKET que int mais c'est la même chose
//c'est un alias
 SOCKET sock;

 //AF_INET c'est pour le protocole TCP/IP.
 //SOCK_DGRAM c'est pour utiliser UDP ( pour tcp c'est SOCK_STREAM
 //on n’utilise pas le champ protocole pour du TCP IP donc on met 0)
 sock = socket(AF_INET, SOCK_STREAM, 0);
 //Fin création de sockets ---------------
 //fonction qui récupère la dernière erreur
 fprintf(stderr, "socket() message: %s\n", strerror(errno));

 //opt = setsockopt(server_fd, SOL_SOCKET, SO_REUSEADDR | SO_REUSEPORT, &opt, sizeof(opt));

 //Structure Info Serveur
 //on appellera notre structure de paramétrage InfoServer
 SOCKADDR_IN InfoServer;

 //htonl est une fonction qui donne automatiquement IP de notre machine
 // mais on peut aussi la forcer sin.sin_addr.s_addr =
//inet_addr("192.168.64.99"); //IP de mon pc
 InfoServer.sin_addr.s_addr = htonl(INADDR_ANY);
//inet_addr("192.168.1.98");//htonl(INADDR_ANY);//INADDR_LOOPBACK;//inet_addr("192.168.1.98");
//htonl(INADDR_LOOPBACK);
 InfoServer.sin_family = AF_INET;
 //htonl est une fonction qui donne le port spécifié en paramètre
 InfoServer.sin_port = htons(9213);
 printf("Demarrage du serveur sur le port N° %d .\n", 9213);
 int error_message;

 error_message = ::bind(sock, (SOCKADDR*)&InfoServer, sizeof(InfoServer));

 if(error_message == 0){
 fprintf(stderr, "Bind message erreur : %s\n", strerror(errno));
 }

 listen(sock,3);
 //accept(sock, (SOCKADDR*)&InfoServer, (socklen_t*)&addrlen);
SOCKET client = accept(sock, NULL, NULL);




 char Messbuffer[1500];
 memset (Messbuffer,'\0',1500);
 sockaddr_in _from;
 socklen_t fromlen = sizeof(_from);
 while(true){


 error_message = recv(client, Messbuffer, 1500, 0);
 if(error_message<1){
 fprintf(stderr, "recvFrom message: %s\n", strerror(errno));
 }else{
 fprintf(stderr,"Client : IP : %s ", inet_ntoa(_from.sin_addr));
 fprintf(stderr," Port : %d ", ntohs(_from.sin_port));
 fprintf(stderr," Message Reçu : %s ", Messbuffer);
 //réponse du serveur
 //A la place de "suis serveur" vous pouvez envoyer l'information que vous souhaitez





 char buffer[]="Bien Recu";


 int octet_message = send(client,buffer,sizeof(buffer),0);
 if(octet_message == 0){
 fprintf(stderr, "sendto message erreur : %s\n", strerror(errno));
 }
 if(Messbuffer[0]=='q'){
 break;
 }
 fprintf(stderr,"\n");
 }

 //important il faut fermer la socket sinon le port reste utilisé par le système
 close(sock);
 close(client);

 return 0;
}
