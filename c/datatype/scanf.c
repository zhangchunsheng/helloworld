#include <stdio.h>
#include <stdlib.h>

int main() {
    int a, b, c, age;
    float scores;

    scanf("a=%d,b=%d,c=%d", &a, &b, &c);
    printf("a+b+c=%d\n\n", (a+b+c));

    fflush(stdin);

    scanf("Tom's age is %d, his scores is %f.", &age, &scores);
    
    printf("age=%d, scores=%f.\n", age, scores);

    return 0;
}
