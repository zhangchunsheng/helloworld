#include <stdio.h>
#include <stdlib.h>

int main() {
    int m = 290;
    float n = 23;
    printf("int = %ld\n", sizeof(m));
    printf("float = %ld\n", sizeof(n));
    printf("double = %ld\n", sizeof(double));
    printf("long = %ld\n", sizeof(long));
    printf("A = %ld\n", sizeof('A'));
    printf("23 = %ld\n", sizeof(23));
    printf("14.67 = %ld\n", sizeof(14.67));

    return 0;
}
