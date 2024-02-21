#include <iostream>   
#include <thread>    
#include <mutex>     
#include <condition_variable>
#include <atomic>     
#include <chrono>     
#include <unistd.h>

using namespace std;


void doThreadBulk(int id) {
    system("node --max-http-header-size 8500 parsers/parserBULK.js");
}
void doThreadMyProtein(int id) {
    system("node parsers/parserMYPROTEIN.js");
}
void doThreadZumub(int id) {
    system("python3 parsers/parserZUMUB.py");
}

int main() {
    // Criando três threads e passando uma função para cada 
    const int NUM_THREADS = 3;

    // Armazenando as threads em um vetor
    vector<thread> threads;

    int i = 0;

    threads.push_back(thread(doThreadBulk, i++));
    threads.push_back(thread(doThreadZumub, i++));
    threads.push_back(thread(doThreadMyProtein, i++));

    // Esperando que todas as threads terminem usando um loop for
    for (auto& t : threads) {
        t.join();
    }

    cout << "Todas as threads terminaram." << endl;

    return 0;
}