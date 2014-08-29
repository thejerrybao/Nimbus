public static int fibonacci(int n) {
	int fib1=1;
	int fib2=1;
	int fib;

	if (n==0) {
		return 0;
	}
	else if (n==1 || n==2) {
		return 1;
	} 	else {
		return fibonacci(n-1) + fibonacci(n-2);
		}
}

public static int[] zipper(int[] first, int[] second) {
	int newLength = first.length() + second.length();
	newArray = int[newLength];

	int i=0, j=0, counter=0;

	while (i<first.length() && j<second.length()) {
		if (first[i] < second[j]) {
			newArray[counter]=first[i]
			i++;
		} else {
			newArray[counter]=second[j];
			j++;
		}
		counter++;
	}

	while (i<first.length()) {
		newArray[counter]=first[i];
		i++;
		counter++;
	}

	while (j<second.length() {
		newArray[counter]=second[j];
		j++;
		counter++;
	}
	return newArray;
}