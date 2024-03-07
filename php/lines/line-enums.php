<?php
// Different kinds of pins
enum LineTypes {
    case TapLine;
    case MainLine;
    case SupportLine;
    case MiscLine;

}

// Format of the description field from DB