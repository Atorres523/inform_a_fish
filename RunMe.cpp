#include <iostream>

class WTF {
public:
    WTF() : val(this) { }
    WTF* operator->() {
        std::cout << "hello ";
        return this;
    }
    WTF &operator*() {
        std::cout << "world";
        return *val;
    }
    WTF *operator,(const WTF &nv) {
        std::cout << "!\n";
        return val;
    }
    WTF *val;
};

int main() {
    WTF val, v2;
    v2 = **(val->val), val;
    return 0;
}