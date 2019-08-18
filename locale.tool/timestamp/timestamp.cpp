// timestamp.cpp: main file

#include "stdafx.h"

using namespace System;

int main(array<System::String ^> ^args)
{
	DateTime tNow = DateTime::Now;
	Console::WriteLine( (tNow.ToUniversalTime().Ticks - 621355968000000000) / 10000000 );
	return 0;
}
